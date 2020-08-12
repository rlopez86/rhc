<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class VisitCounter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->refreshCounter();
        return $next($request);
    }

    private function refreshCounter(){
        $cfg_tbl_users		= 'pcounter_users';
        $cfg_tbl_save		= 'pcounter_save';
        $cfg_online_time	= 10;

        // Daten aus DB auslesen
        $sql = 'SELECT save_name, save_value FROM ' . $cfg_tbl_save;
        $rows =  DB::select($sql);
        $data = array();
        foreach ($rows as $row)
        {
            $data[$row->save_name] = $row->save_value;
        }

        // Aktuellen Tag als julianisches Datum
        $today_jd = GregorianToJD(date('m'), date('j'), date('Y'));

        // Prüfen ob wir schon einen neuen Tag haben
        if ($today_jd != $data['day_time'])
        {
            // Anzahl der Besucher von heute auslesen
            $sql = 'SELECT COUNT(user_ip) AS user_count FROM ' . $cfg_tbl_users;
            $rows = DB::select($sql);
            $today_count = $rows[0]->user_count;

            // Anzahl der Tage zum letzten Update ermitteln
            $days_between = $today_jd - $data['day_time'];

            // Zählerwert von heute auf gestern setzen
            $sql = 'UPDATE ' . $cfg_tbl_save . ' SET save_value=' . ($days_between == 1 ? $today_count : 0) . ' WHERE save_name="yesterday"';
            DB::update($sql);

            // Auf neuen Besucherrekord prüfen
            if ($today_count >= $data['max_count'])
            {
                // Daten aktualisieren
                $data['max_time']  = mktime(12, 0, 0, date('n'), date('j'), date('Y')) - 86400;
                $data['max_count'] = $today_count;

                // Rekordwerd speichern
                $sql= 'UPDATE ' . $cfg_tbl_save . ' SET save_value=' . $today_count . ' WHERE save_name="max_count"';
                DB::update($sql);

                // Aktuellen Tag als neuen Rekordtag speichern
                $sql= 'UPDATE ' . $cfg_tbl_save . ' SET save_value=' . $data['max_time'] . ' WHERE save_name="max_time"';
                DB::update($sql);
            }

            // Gesamtzähler erhöhen
            $sql = 'UPDATE ' . $cfg_tbl_save . ' SET save_value=save_value+' . $today_count . ' WHERE save_name="counter"';
            DB::update($sql);

            // Alte Besucherdaten aus Tabelle entfernen
            $sql = 'TRUNCATE TABLE ' . $cfg_tbl_users;
            DB::update($sql);

            // Datum aktualisieren
            $sql= 'UPDATE ' . $cfg_tbl_save . ' SET save_value=' . $today_jd . ' WHERE save_name="day_time"';
            DB::update($sql);

            // Daten aktualisieren
            $data['counter'] += $today_count;
            $data['yesterday'] = ($days_between == 1 ? $today_count : 0);
        }

        // IP des Besuchers ermitteln
        $user_ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

        // Besucher speichern oder aktualisieren
        $sql = 'INSERT INTO ' . $cfg_tbl_users . ' VALUES (?, ?) ON DUPLICATE KEY UPDATE user_time=?';
        DB::insert($sql, [$user_ip,time(),time()]);

        // Rückgabearray initialisieren
        $output = array();

        // Anzahl der heutigen Besucher auslesen
        $sql = 'SELECT COUNT(user_ip) AS user_count FROM ' . $cfg_tbl_users;
        $row = DB::select($sql);
        $output['today'] = $row[0]->user_count;

        // Gesamte Besucherzahl und Besucher vom Vortag zurückgeben
        $output['counter']   = $data['counter'] + $output['today'];
        $output['yesterday'] = $data['yesterday'];

        // Aktuelle Besucher der letzten x Minuten auslesen
        $sql = 'SELECT COUNT(user_ip) AS user_count FROM ' . $cfg_tbl_users . ' WHERE user_time>=' . (time() - $cfg_online_time * 60);
        $row = DB::select($sql);
        $output['online'] = $row[0]->user_count;

        // Wurde der aktuelle Besucherrekord heute überschritten?
        if ($output['today'] >= $data['max_count'])
        {
            // Heutigen Tag als Rekord zurückgeben
            $output['max_count'] = $output['today'];
            $output['max_time']  = time();
        }
        else
        {

            // Alten Rekord zurückgeben
            $output['max_count'] = $data['max_count'];
            $output['max_time']  = $data['max_time'];
        }

        $this->user_total		= $output['counter'];
        $this->user_online		= $output['online'];
        $this->user_today		= $output['today'];
        $this->user_yesterday	= $output['yesterday'];
        $this->user_max_count	= $output['max_count'];
        $this->user_time		= $output['max_time'];
    }
}
