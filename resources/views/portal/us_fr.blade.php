@extends('layouts.big_master')
@if($main_section->banner)
    @section('banner', 'background: linear-gradient(rgba(90, 112, 159, 0.5), rgba(6, 28, 58, 0.7)), url("'.asset($main_section->banner).'") no-repeat center;background-size:cover;')
@endif
@section('section-title')
    <h1>{{trans('messages.'.$main_section->label)}}</h1>
@endsection
@php $no_mic = true;@endphp
@section('columns')
        <div class="col" id="us">
            <h3>¿... y creen acaso que van a poder ocultar ante el mundo?</h3>
            <blockquote>
                <span class="start"></span>
                <p>
                    No, ya Cuba tiene una planta de radio que hoy se está ya transmitiendo a toda la América Latina, y esto lo están oyendo innumerables hermanos de América Latina y en todo el mundo, por suerte no estamos en la época de la diligencia. Estamos en la época del radio y las verdades se pueden llevar muy lejos.
                </p>
                <span class="over"></span>
            </blockquote>
            <p class="text-right author">Fidel Castro Ruz</p>
            <div class="intro">
                <p>El fragmento anterior del discurso pronunciado por Fidel Castro para despedir el sepelio de las víctimas de los bombardeos a la base aérea de San Antonio de los Baños y los aeropuertos de Ciudad Libertad y Santiago de Cuba, preludio de la agresión mercenaria de "Bahía de Cochinos", anuncia al mundo el 16 de abril de 1961 la apertura de las emisiones radiales cubanas al exterior con la creación de Radio Habana Cuba (RHC), "Una voz de amistad que recorre el mundo".</p>
                <p>Con el triunfo de enero de 1959, el viejo sueño de los "barbudos de la Sierra" se convierte en una tarea medular para la dirección del país: concretar la puesta en marcha de RHC, la voz de un pueblo en Revolución y, por ello, también la voz de todos los que en el mundo luchan contra el imperialismo y por su autodeterminación.</p>
            </div>
            <div class="foundation">
                <h4>RADIO HABANA CUBA</h4>
                <p>Aunque de manera oficial RHC surge el primero de mayo de 1961, la idea de crear una emisora cubana de alcance internacional germina en la propia Sierra Maestra durante la campaña final contra la dictadura de Fulgencio Batista. Tras la creación por el Che en febrero de 1958 de Radio Rebelde, la dirección del ejército guerrillero reflexiona en torno a la posibilidad de establecer luego del anhelado triunfo una radioemisora con potencia suficiente como para llegar a todos los países del mundo con la verdad de la Revolución cubana.</p>
            </div>
            <div class="origins">
                <h4>SUS ORÍGENES</h4>
                <p>En los albores mismos de enero de 1959, las agencias informativas norteamericanas emprendieron su campaña de calumnias contra el proceso revolucionario iniciado en Cuba .</p>
                <p>En aquellos días, cuando eran enjuiciados los verdugos y criminales de guerra de la derrocada tiranía, las trasnacionales noticiosas desvirtuaban la realidad de los hechos y, con descripciones aparentemente objetivas, informaban de las ejecuciones de "adversarios políticos y de partidos de Batista ".</p>
                <p>Estimulada por la avalancha de desinformaciones sobre Cuba que proporcionaban AP, UPI y otras agencias imperialistas, la prensa conservadora del continente agitaba la supuesta violación de las libertades individuales y de las normas de convivencia humana en nuestro país .</p>
                <p>Cada día, más organizadas se sucedían esas campañas una tras otra. Intensamente se desarrolló una en América Latina y Estados Unidos para indisponer contra la Revolución a los hombres y mujeres con sentimientos religiosos. Con tal fin divulgaron la calumniosa versión de persecuciones y asesinatos de sacerdotes y la clausura de templos e iglesias. Después continuaron con la de la "patria potestad" y otras muchas campañas tendenciosas .</p>
                <p>Los especialistas de la propaganda, pagados por los enemigos de la Revolución, no cesaron en la innoble tarea de fabricar nuevas calumnias para lo cual disponían de sus agencias informativas y otros poderosos medios de difusión .</p>
                <p>Para contrarrestar aquella oleada de mentiras, convocó Cuba a la "Operación Verdad". Casi 400 periodistas de todo el mundo viajaron a La Habana y, los que quisieron y pudieron, reflejaron verazmente lo que vieron en la isla, realidad que no se correspondía con lo que difundía la prensa escrita, la radio y la televisión del continente. Sin embargo, aquello subdesarrollado, surgía poco después un vehículo capaz de llevar a todo el mundo la verdad de la Revolución: Radio Habana Cuba (RHC).</p>
                <p>La primera y única emisora cubana, de ondas cortas para la radiodifusión internacional, nacía como una necesidad vitalísima de la Revolución Cubana: la de contar con un medio radial de ese alcance que fuera capaz de quebrar el bloqueo informativo establecido en torno a nuestro país por los órganos propagandísticos de Estados Unidos y de sus regímenes adictos, y de enfrentarse a la bien orquestada campaña de calumnias elaboradas por el imperialismo norteamericano.</p>
                <p>Al iniciarse el año 1961, el gobierno de Estados Unidos, había logrado ya, en forma y proporción considerables, el aislamiento de Cuba del resto del mundo, especialmente de América Latina, con cuyos pueblos estamos unidos por lazos históricos, lingüísticos y culturales, al igual que por realidades económicas y sociales comunes.</p>
                <p>Todos los recursos propagandísticos del imperio se dirigían fundamentalmente a callar el ejemplo que nuestra Revolución significa para los pueblos. Lo secundaba en esos trajines todo el andamiaje de intereses oligárquicos de Latinoamérica.</p>
                <p>¿Qué otra cosa ocultaba también el imperialismo por aquellos meses de 1960 y 1961 con el bloqueo informativo y cultural contra Cuba.?</p>
                <p>El silencio era un magnifico auxiliar para emprender la agresión y el crimen, para destruir, mediante la acción militar, el ejemplo revolucionario que emanaba de Cuba. Resultaba indispensable la complicidad del silencio.</p>
                <p>Mientras el pueblo cubano se preparaba para defender la soberanía e integridad de su territorio, se escuchaban ya en las semanas iniciales de 1961, a manera de ensayo, pequeños programas confeccionados en la primera radioemisora internacional de Cuba . La identificación provisional, "Onda Corta experimental ", surcaba el espacio y ganaba oyentes en el exterior .</p>
            </div>
            <div class="truth">
                <h4>NO PODRÁN OCULTAR LA VERDAD</h4>
                <p>Con el mensaje inicial lanzado en febrero de 1961, se abría la primera brecha en el cerco informativo a Cuba. Un pequeño trasmisor dirigió los primeros programas, en español, a la zona de América Central. Concluidos esos ensayos, la que poco después se denominaría Radio Habana Cuba, recibía su bautismo de fuego durante la invasión de los mercenarios por Playa Girón en abril de ese año.</p>
                <p>El 15 de abril Radio Habana Cuba informaba sobre el ataque a tres aeropuertos cubanos que perseguía el objetivo de destruir, en tierra, los pocos aviones de combate con que contaba nuestra incipiente fuerza aérea, y facilitar de ese modo a los mercenarios la invasión que llevarían a cabo dos días después por Playa Larga y Playa Girón.</p>
                <p>El domingo 16 difundió la emisora la despedida de duelo que el Comandante en Jefe Fidel Castro pronunció en el sepelio de las víctimas de los bombardeos, ocasión en la que proclamó el carácter socialista de la Revolución Cubana. En aquel histórico discurso y cuando los imperialistas norteamericanos confiaban en que la impunidad del silencio les permitiría ocultar la verdad sobre la artera agresión, declaraba Fidel Castro: "¿Creen acaso que van a poder ocultarlo ante el mundo... ? No. Ya Cuba tiene una planta de radio que ya está trasmitiendo a toda la América Latina, y esto lo están oyendo innumerables hermanos en la América Latina, y de todo el mundo".</p>
                <p>Mientras nuestros combatientes peleaban en la península de Zapata para expulsar a los invasores, Radio Habana Cuba denunciaba la agresión imperialista, difundía los comunicados de prensa sobre el desarrollo de los acontecimientos y, por último, el de la decisiva victoria de nuestro pueblo. Poco a poco las notas de la Marcha del 26 de Julio, identificación de la novel emisora, recorrerían el espacio sin interrupciones, días tras días y hora tras hora.</p>
                <p>El primero de mayo de 1961, fecha en que nuestro pueblo celebró públicamente la victoria de Playa Girón, se inauguró de manera oficial Radio Habana Cuba; ahí comenzó el proceso de crecimiento. ¡Ya había algo más que aquel modesto y pequeño trasmisor que sirvió para la onda experimental. Su mensaje se extendió a toda la América del Sur y del Norte, luego creció con los años hasta llegar a los nueve idiomas actuales: español, francés , portugués , árabe , quechua , guaraní , cróele y esperanto.</p>
                <p>Ubicada en una de las avenidas más céntricas de La Habana, la monumental Infanta, Radio Habana Cuba comparte un emblemático inmueble en la historia de la Radiodifusión cubana con dos emisoras de alcance nacional "Radio Progreso", La onda de la alegría, y CMBF, Radio Musical Nacional.</p>
                <p>Nuestras emisiones llegan hoy al mundo en nueve idiomas con más de 30 horas diarias de programación, esencialmente noticiosa, aunque también dedicamos importantes espacios a lo más representativo de nuestra rica producción musical nacional.</p>
                <p>Escuchar pues, Radio Habana Cuba, es adentrarse en la vida de un pueblo que construye, día a día, su destino, sin injerencia ni recetas impuestas desde fuera.</p>
            </div>
            <div class="years">
                <h4>Años ininterrumpidos de contribución noticiosa a las redes digitales mundiales</h4>
                <p>Radio Habana Cuba comenzó a ofertar información en la red, incluso desde antes que existiera Internet. Fue en 1992, cuando el departamento de inglés de la emisora empezó a colaborar en la conferencia "reg.cuba" de las redes igc.apc con sede en California, enviando diariamente todas las noticias, tanto nacionales e internacionales que se redactaban en inglés.</p>
                <p>La primera página Web que tuvo Radio Habana Cuba, (RHC) se inauguró para el aniversario de la emisora el 1 de mayo del 1997. Para esa época todavía el acceso a Internet en el país era muy limitado. Esta página fue diseñada por los compañeros de Blythe Systems, con sede en Nueva York, que a su vez la hospedaron en su página conocida como New York Transfer de noticias alternativas.</p>
                <p>En el año 1997, con la mejoría tecnológica, Radio Habana Cuba asume la creación de su página Web, que radica en Cuba, en el servidor de Colombus. Y para lo cual adquirió el dominio, www.radiohc.cu Se le añadieron importantes secciones, servicios y la transmisión del audio real por Internet, por la cual se puede escuchar la emisora desde cualquier latitud del planeta.</p>
                <p>En septiembre del 2002 se comienza a rediseñar la página, manteniendo los cuatro idiomas de la misma (ingles, español, francés y Portugués), y se le adiciona un sitio de los cinco patriotas cubanos en seis idiomas (ingles, español, francés, creol, esperanto y Portugués).</p>
                <p>En abril del 2003 el sitio Web de Radio Habana Cuba recibe el Gran Premio en el XXV Festival Nacional de la Radio que por primera vez se otorga a las páginas Web de la Radiodifusión cubana.</p>
                <p>En febrero del 2004 el sitio Web de Radio Habana Cuba recibe por segunda vez el Gran Premio en el XXVI Festival Nacional de la Radio que se otorga a las páginas Web de la Radiodifusión cubana.</p>
            </div>
        </div>
@endsection