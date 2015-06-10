@extends('layouts.master')

@section('title')
@parent
| informácie o výstave
@stop

@section('content')

<section class="info">
    <div class="container">
        <div class="row content-section">
            <div class="col-md-12 text-center">
                    <h2>Čo je web umenia?</h2>
            </div>
        </div>
        <div class="row bottom-space">
            <div class="col-md-4">

                <p class="lead">Web umenia je <strong>on-line katalóg</strong> výtvarných diel zo zbierok <strong>slovenských galérií</strong>.</p>

                <p>Nájdete tu základné informácie o dielach a ich autoroch, ale aj pôvodné články, videá a kolekcie. Údaje a digitálne reprodukcie sú preberané a pravidelne aktualizované zo systému CEDVU (Centrálna evidencia diel výtvarného umenia), kam ich vkladajú zamestnanci jednotlivých galérií. Vo viacerých prípadoch nie sú záznamy úplné a niektoré údaje môžu chýba, rovnako sa môžu vyskytnúť nepresnosti. Pred publikovaním je preto nutné si správnosť údajov overiť.</p>
            </div>
            <div class="col-md-4 text-center">
                <ul class="list-unstyled lead">
                    <li><a href="{{ URL::to('katalog?gallery=Slovenská národná galéria, SNG') }}">Slovenská národná galéria</a></li>
                    <li><a href="{{ URL::to('katalog?gallery=Oravská galéria, OGD') }}">Oravská galéria Dolný Kubín</a></li>
                    <li><a href="{{ URL::to('katalog?gallery=Galéria umenia Ernesta Zmetáka, GNZ') }}">Galérie umenia Nové Zámky Ernesta Zmetáka</a></li>
                    <li><a href="{{ URL::to('katalog?gallery=Liptovská galéria Petra Michala Bohúňa, GPB') }}">Liptovská galéria Petra Michala Bohúňa</a></li>
                    <li><a href="{{ URL::to('katalog?gallery=Galéria mesta Bratislavy, GMB') }}">Galéria mesta Bratislavy</a></li>
                </ul>
            </div>
            <div class="col-md-4 text-center">
                <img src="/images/galerie-na-mape.png" alt="Galérie na mape" class="img-responsive" style="margin-top: 10px" />
            </div>
        </div>
    </div>
</section>
<section class="mozete">
    <div class="container">
        <div class="row content-section">
            <div class="col-md-12 text-center grey">
                    <h2 class="inherit">U nás môžete</h2>
            </div>
        </div>
        <div class="row ">
            <!-- stahovanie -->
            <div class="col-md-4">
                <h4>Sťahovať diela a využívať ich</h4>
                <ul>
                    <li>Digitálne reprodukcie diel SNG na tejto stránke sú sprístupnené ako <a href="https://creativecommons.org/publicdomain/zero/1.0/" target="_blank"><strong>verejné vlastníctvo (public domain)</strong></a>. Môžete si ich voľne stiahnuť <strong>vo vysokom rozlíšení</strong> a využívať na súkromné aj komerné účely - kopírovať, zdieľať i upravovať. </li>

                    <li>Pri ďalšom šírení <strong>prosíme uviesť</strong> meno autora, názov, majiteľa diela a zdroj (webummenia.sk) </li>

                    <li>Ak plánujete využiť reprodukcie na <strong>komerčné účely</strong>, prosím informujte o vašich plánoch vopred, naši odborníci vám vedia poradiť.</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h4>napríklad</h4>
                <ul>
                    <li><strong>tlač</strong> (plagáty, pohľadnice alebo tričká)</li>

                    <li><strong>vlastná tvorba</strong> (digitálna úprava reprodukcie, využitie jej časti pre animáciu alebo koláž)</li>

                    <li><strong>vzdelávanie</strong> (vloženie obrázku na vlastnú webstránku, použitie na Wikipedii i ako súhlas prezentácie)</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h4>Objednať si kvalitné reprodukcie</h4>
                <p>K vybraným dielam zo zbierok SNG ponúkame možnosť objednať si reprodukcie v archívnej kvalite na fineartových papieroch. Po výbere diel, vyplnení údajov a odoslaní objednávky vás bude kontaktovať pracovník SNG s podrobnejšími informáciami. Momentálne je možné vyzdvihnúť si diela len osobne v kníhkupectve <a href="http://www.sng.sk/sk/uvod/navsteva-sng/sluzby/knihkupectvo-ex-libris" target="blank" class="strong">Ex Libris v priestoroch SNG</a> na <a href="https://goo.gl/maps/k0sBz" target="_blank" class="strong">Námestí. Štúra 4 v Bratislave</a>.</p>
            </div>

        </div>
        <div class="row top-space">
            <div class="col-md-12">
                <a href="{{ URL::to('katalog?is_free=' . '1') }}" class="inherit lead pull-left no-border"><i class="icon-arrow-right"></i> &nbsp; voľne stiahnutelné diela</a>

                <a href="#" data-toggle="modal" data-target="#priceList" class="inherit lead pull-right no-border"><i class="icon-arrow-right"></i> &nbsp; cenník reprodukcií</a>
            </div>
        </div>

    </div>
</section>
<section class="mozete more-items">
    <div class="container">
        <div class="row top-space bottom-space">   
            <div class="col-xs-12 text-center">
                <h3>Reprodukcie pre vás</h3>
            </div>  
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="artworks-preview ">
                @foreach ($items as $item)
                    <a href="{{ $item->getDetailUrl() }}"><img data-lazy="{{ $item->getImagePath() }}" class="img-responsive-width " ></a>
                @endforeach
                </div>
            </div>
        </div>
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{{ url_to('katalog', ['gallery' => 'Slovenská národná galéria, SNG']) }}" class="btn btn-default btn-outline sans" >zobraziť všetkých <strong>{{ Item::forReproduction()->count() }}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div tabindex="-1" class="modal fade" id="priceList" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2>cenník reprodukcií</h2>
            </div>
            <div class="modal-body">
                
                <p>Ceny sú len orientačné</p>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-right">do formátu A4</th>
                            <th class="text-right">od A4 do A3+</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>samostatná reprodukcia</strong></td>
                            <td class="text-right">25 €</td>
                            <td class="text-right">35 €</td>
                        </tr>
                        <tr>
                            <td><strong>reprodukcia s paspartou</strong></td>
                            <td class="text-right">35 €</td>
                            <td class="text-right">50 €</td>
                        </tr>
                        <tr>
                            <td><strong>s paspartou a rámom</strong></td>
                            <td class="text-right">40 €</td>
                            <td class="text-right">60 €</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <div class="text-center"><button type="button" data-dismiss="modal" class="btn btn-default btn-outline sans">zavrieť</button></div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
{{ HTML::script('js/slick.js') }}
<script type="text/javascript">
    $(document).ready(function(){

        $('.artworks-preview').slick({
            dots: false,
            lazyLoad: 'progressive',
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            slide: 'a',
            centerMode: false,
            variableWidth: true,
        });

    });
</script>

@stop
