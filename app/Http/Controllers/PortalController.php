<?php

namespace App\Http\Controllers;

use App\Article;
use App\Captcha;
use App\Comment;
use App\Correo;
use App\Gallery;
use App\Language;
use App\Program;
use App\ProgramSchedule;
use App\Propaganda;
use App\Registro;
use App\Ribbon;
use App\Section;
use App\VisitCounter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Spatie\Feed\Http\FeedController;
use DOMDocument;
use PhpParser\Node\Stmt\Foreach_;

class PortalController extends Controller
{
    public function home(){
        $used = [];
        $articles = Article::getPortada(app()->getLocale());
        $used = $this->merge_with_used($used,$articles);

        $nationals = Article::getBySectionsLabel('nacionales', app()->getLocale(), 7, $used);
        $used = $this->merge_with_used($used,$nationals);

        $internationals = Article::getBySectionsLabel('internacionales', app()->getLocale(), 7,$used);
        $used = $this->merge_with_used($used,$internationals);

        $sports = Article::getBySectionsLabel('deportes', app()->getLocale(), 6,$used);
        $this->merge_with_used($used,$sports);

        $culture = Article::getBySectionsLabel('cultura', app()->getLocale(), 6,$used);
        $this->merge_with_used($used,$culture);

        $comments = Article::getBySectionsLabel('comentarios', app()->getLocale(), 3,$used);
        $this->merge_with_used($used,$comments);

        $exclusives = Article::getBySectionsLabel('exclusivas', app()->getLocale(), 3,$used);
        $this->merge_with_used($used,$exclusives);
        $latest = Article::getLatestCloud($nationals->concat($articles)->concat($internationals)->concat($sports)->concat($culture)->concat($comments)->concat($exclusives));
        $ribbons = Ribbon::where('publicado', 1)->where('idioma', app()->getLocale())->get();
	$multimedia = Article::getLatestWithMedia(app()->getLocale(), 6);
        $galleries = Gallery::getLatest(6);
        $multimedia = $multimedia->concat($galleries);
        $multimedia->sortByDesc('fecha');

        $news_children = Section::getNewsSections(app()->getLocale());
        $most_visited = Article::getMostVisited(app()->getLocale(), 5);
        $ignore_latest = [];
        foreach ($articles as $ar){
            $ignore_latest[] = $ar->idarticulos;
        }
        $data = $this->fetchDefaults($ignore_latest);
        $data['news_children'] = $news_children;
        $data['portada'] = $articles;
        $data['nationals'] = $nationals;
        $data['internationals'] = $internationals;
        $data['sports'] = $sports;
        $data['culture'] = $culture;
        $data['comments'] = $comments;
        $data['exclusives'] = $exclusives;
        $data['most_visited'] = $most_visited;
        $data['latest'] = $latest;
        $data['multimedia'] = $multimedia;
        $data['ribbons'] = $ribbons;

        //return $data;
        return view('welcome', $data);
    }
    public function merge_with_used($used,$articles)
    {
        foreach ($articles as $art) {
            array_push($used, $art->idarticulos);
        }
        return $used;
        
    }

    public function sectionArticles(Request $request, $language='es', $article=false){
        //echo('Lenguaje: '.$language);
        //print('Locale: '.app()->getLocale());
        if(!$article && !Language::abrevs()->contains($language)){
            return $this->resolveArticle($request, $language);
        }
        //print('Articulo: '.$article);
        if($article){
            return $this->resolveArticle($request, $article);
        }
        $path = $request->path();
        if(app()->getLocale()!=config('app.fallback_locale')){
            $path = substr($path, strlen(app()->getLocale()));
        }
        //echo('Path: '.$path);
        $section = Section::getByPath($path);
        //print('Section: '.$section);
        //echo($section);
        $articles = Article::getLatestByBranch($section, $language, false, 15);
        //echo($articles[0]);
        $data = $this->fetchDefaults([], false);
        $data['main_section'] = $section;
        $data['articles'] = $articles;
        $data['most_visited'] = Article::getMostVisited(app()->getLocale(), 5);
        return view('portal.section', $data);
    }

    public function resolveArticle(Request $request, $article_identifier){
        $article = Article::findOrFail(explode('-', $article_identifier)[0]);
        $path = $request->path();
        if(app()->getLocale()!=config('app.fallback_locale')){
            $path = substr($path, strlen(app()->getLocale()));
        }
        $path = str_replace('/'.$article_identifier, '', $path);
        $section = Section::getByPath('/'.$path);
        $data = $this->fetchDefaults([], false, true,true,true,true,false);
        $most_visited = $most_visited = Article::getMostVisited(app()->getLocale(), 5);
        $data['main_section'] = $section;
        $data['article'] = $article;
        $data['brand'] = $section->label;
        $data['related'] = $article->getRelated();
        $data['most_visited']= $most_visited;
        if($article->sections->contains($section)){
            $article->increment('visitas');
            return view('portal.article', $data);
        }
        else
            abort(404);

    }

    public function podcasts(){
        $programs = Program::with('audios')->where('habilitado', 1)->where('idioma', app()->getLocale())->orderBy('orden', 'desc')->get();
        $sections = Section::tree(app()->getLocale());
        $programacion = ProgramSchedule::getTodaySchedule();
        return view('portal.podcasts_page', ['programs'=>$programs, 'sections'=>$sections['children'],
            'programacion'=>$programacion]);
    }

    public function staticUs(){
        $data = $this->fetchDefaults([], false, true, true,false,true,false);
        
        $main_section = Section::where('label', 'quienes-somos')->first();
        $data['main_section'] = $main_section;
        return view('portal.us_'.app()->getLocale(), $data);
    }

    public function schedule(){
        $data = $this->fetchDefaults([],false, true, true, false, false,true);
        
        $main_section = Section::where('label', 'programacion')->first();
        $file = 'programacion.pdf';
        $data['main_section'] = $main_section;
        $data['file'] = $file;
        return view('portal.pdf', $data);
    }

    public function frequences(){
        $data = $this->fetchDefaults([], false, true, true,false,true,false);
        $main_section = Section::where('label', 'frecuencias')->first();
        $file = 'frecuencias.'.app()->getLocale().'.pdf';
        $data['main_section'] = $main_section;
        $data['file'] = $file;
        return view('portal.pdf', $data);
    }

    public function getMails(){
        $data = $this->fetchDefaults([],false, true,true,true,true,true);
        
        $main_section = Section::where('label', 'correspondencia')->first();
        $mails = Correo::where('publicado', 1)->orderBy('fecha', 'desc')->paginate(5);
        $data['main_section'] = $main_section;
        $data['mails'] = $mails;
        return view('portal.correspondencia', $data);
    }

    public function getPdf($filename){
        if(preg_match('/^[a-z.]+$/', $filename)){
            $filepath = public_path('pdfs/'.$filename);
            if(file_exists($filepath)){
                // Set up PDF headers
                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="' . $filename . '"');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($filepath));
                header('Accept-Ranges: bytes');

                // Render the file
                readfile($filepath);
            }
            else{
                abort(404);
            }
        }
        else
            abort(404);
    }

    public function generateCaptcha(){
        $c = new Captcha();
        $c->buildCaptcha();
        $image = $c->getImage();
        session()->put('captcha_code',$c->getCode());
        header("Content-type: image/png");
        imagepng($image);
        $c->release();
    }

    public function mailSend(Request $request){
        $request->validate([
            'name'=>'required',
            'country'=>'required',
            'email'=>'required|email',
            'text'=>'required',
            'captcha'=>'required|in:'.session()->get('captcha_code').','
        ]);
        $mail = new Correo;
        $mail->autor = $request->input('name');
        $mail->pais = $request->input('country');
        $mail->correo = $request->input('email');
        $mail->texto = $request->input('text');
        $mail->publicado = 0;
        $mail->fecha = Carbon::now();
        if($mail->passFilters())
            $mail->save();
        return back()->with('sended', 'sended');
    }

    public function commentSend(Request $request){
        $request->validate([
            'nombre'=>'required',
            'correo'=>'required|email',
            'texto'=>'required',
            'articulo'=>'required',
            'captcha'=>'required|in:'.session()->get('captcha_code').','
        ]);
        $mail = new Comment();
        $mail->autor = $request->input('nombre');
        $mail->correo = $request->input('correo');
        $mail->texto = $request->input('texto');
        $mail->articulo = $request->input('articulo');
        $mail->publicado = 0;
        $mail->fecha = Carbon::now();
        $mail->paises_idpais = 0;
        $mail->save();
        return back()->with('sended', 'sended');
    }

    private function fetchDefaults($ignore_latest=[], $fetch_latest=true, $fetch_sections=true, $fetch_langs=true,
                                   $fetch_counters=true, $fetch_programacion = true, $fetch_propaganda = true, $path = ''){
        $data = [];
        if($fetch_sections)
            $data['sections'] = Section::tree(app()->getLocale())['children'];
        if($fetch_langs)
            $data['languages'] = Language::where('habilitado', 1)->get();
        if($fetch_counters)
            $data['counters'] = VisitCounter::getCounter();
        if($fetch_programacion)
            $data['programacion'] = ProgramSchedule::getTodaySchedule();
        if($fetch_propaganda)
            $data['propaganda'] = Propaganda::getPropaganda(app()->getLocale());
        if($fetch_latest)
            $data['latest'] = Article::getLatestGeneral(app()->getLocale(), 5, $ignore_latest);
        $data['url_path'] = $this->Get_URL_Path();
        return $data;
    }
    public function Get_URL_Path(){
        //Returns an array of 3 elements, first de href, second the text and third if it is going to be highlighted or not
        $url = $_SERVER["REQUEST_URI"];
        $path_array = explode('/',$url);
        $home= '/';
        if(app()->getLocale() != 'es')
            {
                $home.=app()->getLocale();
                $i=2;
            }
        else{$i=1;}
        $path_with_url = [[$home,'home',true]];
        for ($i; $i < count($path_array); $i++) { 
            if($path_array[$i] == '')
                continue;
            if(preg_match("/[a-z 0-9]\?[a-z 0-9]/",$path_array[$i]))
                {

                    $element_without_query = explode('?',$path_array[$i])[0];
                    array_push($path_with_url,[implode('/',array_slice($path_array,0,$i+1)),$element_without_query,true]);
                continue;
            }
            if(preg_match("/^[0-9]{1,7}/",$path_array[$i]))
            {
                if(preg_match("/-/",$path_array[$i]))
                    array_push($path_with_url,[implode('/',array_slice($path_array,0,$i+1)),'Articulo',false]);
                else
                    array_push($path_with_url,[implode('/',array_slice($path_array,0,$i+1)),'Galeria',false]);
                continue;
            }
            
            
            array_push($path_with_url,[implode('/',array_slice($path_array,0,$i+1)),$path_array[$i],true]);
        }
        return $path_with_url;
    
    } 
    public function audio_en_tiempo_real(Request $request)
    {
        $data = $this->fetchDefaults([], false);
        
        $data['day'] = Carbon::today()->dayOfWeek;
        $data['now'] = Carbon::now()->toTimeString();
        $data['current'] = ProgramSchedule::where('day',$data['day'])
        ->where('hour','<',$data['now'])
        ->orderBy('hour','desc')
        ->first();
        if($data['current'] == null)
        {
        $data['current'] = ProgramSchedule::where('day',$data['day']-1)
        ->orderBy('hour','desc')
        ->first();
        }
        
        $data['next']=ProgramSchedule::where('day',$data['day'])
        ->where('hour','>',$data['now'])
        ->orderBy('hour','asc')
        ->limit(3)
        ->get();
        $data['programs'] = ProgramSchedule::orderBy('hour','asc')->get();
        
        return view('portal.audio_en_tiempo_real',$data);
    }

    public function getGallery(Request $request, $gallery){
        $gal = Gallery::findOrFail($gallery);
        $data = $this->fetchDefaults([], false, true,true,false,false,false);
        
        $data['gallery']=$gal;
        return view('portal.gallery', $data);
    }

    public function getPrint(Request $request, $id){
        $article = Article::findOrFail($id);
        //return view('print.article', ['article'=>$article, 'referrer'=>$request->fullUrl()]);
        $html = view('print.article', ['article'=>$article]);
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $tags = $doc->getElementsByTagName('img');
        foreach ($tags as $tag){
            $imgSrc = $tag->getAttribute('src');
            $tag->setAttribute('src', url($imgSrc));
        }
        $html = $doc->saveHTML();
        //echo $html;
        $pdf = PDF::loadHtml($html);
        return $pdf->download('article.pdf');
    }

    public function getMedia(Request $request){
        $type = $request->input('type');
        $id = $request->input('id');
        if($type == 'video'){
            $art = Article::findOrFail($id);
            return view('portal.youtube',['url'=>$art->video]);
        }
        else{
            $gal = Gallery::findOrFail($id);
            return view('portal.gallery-preview', ['gallery'=>$gal]);
        }
    }

    public function bulletinRegister(Request $request){
        $request->validate([
            'mail'=>'required|email|unique:registros,email'
        ]);
        $mail = $request->input('mail');
        $register = new Registro;
        $register->email = $mail;
        $register->idioma = app()->getLocale();
        $register->fecha = Carbon::today();
        $register->salt = str_random(5);
        $register->activo = 1;
        $register->save();
        return back()->with('registered', '1');
    }

    public function getSearch(Request $request){
        $query = $request->input('query');
        $from = $request->input('from', false);
        $to = $request->input('to', false);
        $articles = Article::searchQuery($query, app()->getLocale(), $from, $to);
        $data = $this->fetchDefaults([], false);
        
        $data['main_section'] = Section::getSearchSection();
        $data['articles'] = $articles;
        $data['query'] = $query;
        $data['from'] = $from;
        $data['to'] = $to;
        return view('portal.search', $data);
    }

    
     public function getGalleries(Request $request){
        $data = $this->fetchDefaults([], false);
        $path = $request->path();
        if(app()->getLocale()!=config('app.fallback_locale')){
            $path = substr($path, strlen(app()->getLocale()));
        }
        $section = Section::getByPath($path);
        $data['main_section'] = $section;
        $data['galleries'] = Gallery::where('publicado', 1)->paginate(12);
        return view('portal.galleries', $data);
    }

}
