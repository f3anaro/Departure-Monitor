<html>
<head>
<style type="text/css">
<!--
	body { 
		background-color:black;
		}
	.schrift {
		font-size:40px;
		font-family:Arial;
		z-index:3;
		margin-left:36%; 
		margin-top:6%;		
		}
	.transparenterhintergrund {
     		background:rgba(255,0,0,0.7);
 		}
	.wetterboxhaed { 
		width:100%; 
		height:150px; 
		background-color:black; 
		opacity:0.5; 
		border-radius: 20px; 
		font-family:fantasy; 
		font-size:30px;
		}
	.wetterbox {
		z-index:1;
		width:400px; 
		height:550px; 
		background:rgba(0,0,102,0.7); 
		border-radius: 20px;
		margin-right:0.5%;
		}
	.innenformatierungbild {
		z-index:3;
		margin-left:36%; 
		margin-top:0%;
		}
	.innenwettertext {
		z-index:3;
		font-size:30px;
		font-family:Arial;
		text-align:center; 
		margin-top:0%;
		}
	.temp {
		z-index:3;
		font-size:40px;
		font-family:Arial;
		margin-left:27.5%; 
		margin-top:-12%;
		}
	.wind {
		z-index:3;
		font-size:15px;
		font-family:Arial;
		margin-left:12.5%; 
		margin-top:2%;
		}
	.niederschlag {
		z-index:3;
		font-size:15px;
		font-family:Arial;
		margin-left:12.5%; 
		margin-top:5.5%;
	}
	.tempsymmax {
		z-index:3;
		margin-left:10%; 
		margin-top:3%;
	}
	
	
--> 
</style>
<style type="text/css">

  td.sebastian {
    border-left: 8px solid #000;
  }

  td {
    border-bottom: 1px solid #000;
  }
</style>
<meta charset="utf-8" />
</head>
<body text='#000000' link='#FF0000' alink='#FF0000' vlink='#FF0000'>
<?php

/*
Hey, hier die webseite für unseren Abfahrtsmonitor !!!
diese Seite wird über ein Raspberry abgerufen und angezeigt.
auf der linken Seite ist der Abfahrtsmonitor für unsere Haltestellen in der Nähe und rechts das Wetter für den Tag.
Ich würde gern die Seite nur beim ersten Aufruf laden lassen und dann soll sich nur noch das ändern was sich auch wirklich ändert !!!
also die Abfahrtszeiten,Liniennummer und Linienname (alle 15 sec) die Uhrzeit (am besten genau) die Laufschrift (jede min.) und natürlich das Wetter (jede Stunde)
ich hoffe, dass du helfen kannst und das der Quelltext ausreichend kommentiert ist.
nicht wundern, aber die formatierung muss ich noch fürs Raspberry ändern !!! im Hintergrund vom Wetter will ich normalerweise noch Bilder anzeigen lassen !!! 
ich habe leider von ajax keine ahnung ...
LG
Flo 
*/


/* Laufschrift unter der Abfahrtszeitenanzeige */
$zufall = rand(1,10);																	
switch($zufall) {
  case '1': $laufschrift = 'Simon, geh mal Bier holn !!!'; break;
  case '2': $laufschrift = 'Mops, wer hat die Gans gestohlen ?'; break;
  case '3': $laufschrift = 'Gl&uuml;ck Auf !!!'; break;
  case '4': $laufschrift = 'Socken wanted! Das Sockenmonster geht um...'; break;
  case '5': $laufschrift = 'TUUUUT TUUUT ... Zug im anrollen!'; break;
  case '6': $laufschrift = 'Willkommen in der besten WG diesseits der Galaxie!'; break;
  case '7': $laufschrift = 'Was geht aaab ???'; break;
  case '8': $laufschrift = 'Putzplan f&uuml;r kommende Woche: K&uuml;che: Basti, Bad: Basti, Flur: Basti'; break;
  default:  $laufschrift = 'No news for today...'; break;
}
/* Zeit und Datum Für den Abfahrtsmonitor */
$timeget = time();																
$uhrzeit = date('H:i',$timeget);
$timeget = time();
$datum = date('d.m.Y',$timeget);

$filestring= file_get_contents('http://widgets.vvo-online.de/abfahrtsmonitor/abfahrten.do?ort=Dresden&hst=Lene-Glatzer-Stra%C3%9Fe&lim=6');		
/* Adresse wo die abfahrtszeiten herkommen für die rechte Seite (Lene-Glatzer Straße) */
$filestring=str_replace('"],["',"µ",$filestring);
$filestring=str_replace('[["',"µ",$filestring);
$filestring=str_replace('"]]',"µ",$filestring);
$filestring=str_replace('","',"µ",$filestring);
$filestring=str_replace('6/12',"",$filestring);

$abfahrt  = "$filestring";
$position = explode("µ", $abfahrt);
/* Teilt den eingelesenen string in ein array mit Liniennummer,Linienname und der Abfahrtszeit in min. z.b. $position[1]-->Liniennummer,$position[2]-->Linienname,$position[3]-->Abfahrtszeit, gehören also in eine Zeile !! */ 


$filestring = file_get_contents('http://widgets.vvo-online.de/abfahrtsmonitor/abfahrten.do?ort=Dresden&hst=Jacobi-Stra%C3%9Fe&lim=6');
/* Adresse wo die abfahrtszeiten herkommen für die linke Seite (Jacobi Straße) */
$filestring=str_replace('"],["',"µ",$filestring);
$filestring=str_replace('[["',"µ",$filestring);
$filestring=str_replace('"]]',"µ",$filestring);
$filestring=str_replace('","',"µ",$filestring);

$abfahrt1  = "$filestring";
$position1 = explode("µ", $abfahrt1);
/* gleiche wie oben jetzt für die andere Haltestelle das array ist nun !!! $position1 !!! $position1[1]-->Liniennummer,$position1[2]-->Linienname,$position1[3]-->Abfahrtszeit */

// nun das Wetter
$apiadresse = 'http://api.wetter.com/forecast/weather/city/DE0002265010/project/wetteranzeigewg/cs/dbc178aa617f85fd27676d00ae85332e';
/* api wo die Wetterdaten herkommen */
$api = simplexml_load_string(file_get_contents($apiadresse));
	$tag=0;
	
	/*Wetter heute*/
		$wettertag=$api->forecast->date[$tag]->w;
        	$wetter_text= $api->forecast->date[$tag]->w_txt;
			$datum_heute= date('d.m.Y');
/* wetter für heute früh (array 0) bis heute Nacht (array 4) date steht für heut (0) oder morgen (1) !!! */
			$wetter=$api->forecast->date[0]->time[0]->w;
			$wetter_heute_frueh=substr($wetter,0,1);

			$wetter=$api->forecast->date[0]->time[1]->w;
			$wetter_heute_mittag=substr($wetter,0,1);

			$wetter=$api->forecast->date[0]->time[2]->w;
			$wetter_heute_abend=substr($wetter,0,1);

			$wetter=$api->forecast->date[0]->time[3]->w;
			$wetter_heute_nacht=substr($wetter,0,1);
/* nun der wetterzustand nochmal in Textform */
			$wetter_text_frueh=$api->forecast->date[0]->time[0]->w_txt;
			$wetter_text_mittag=$api->forecast->date[0]->time[1]->w_txt;
			$wetter_text_abend=$api->forecast->date[0]->time[2]->w_txt;
			$wetter_text_nacht=$api->forecast->date[0]->time[3]->w_txt;
/* min/max Temperatur */
			$min_temp_heute_frueh=$api->forecast->date[0]->time[0]->tn;
			$max_temp_heute_frueh=$api->forecast->date[0]->time[0]->tx;				
			$min_temp_heute_mittag=$api->forecast->date[0]->time[1]->tn;
			$max_temp_heute_mittag=$api->forecast->date[0]->time[1]->tx;
			$min_temp_heute_abend=$api->forecast->date[0]->time[2]->tn;
			$max_temp_heute_abend=$api->forecast->date[0]->time[2]->tx;
			$min_temp_heute_nacht=$api->forecast->date[0]->time[3]->tn;
			$max_temp_heute_nacht=$api->forecast->date[0]->time[3]->tx;
/* Niederschlagswahrscheinlichkeit */
			$niederschlags_ws_heute=$api->forecast->date[0]->pc;
/* Windgeschwindigkeit */
			$wind_geschwindigkeit_heute=$api->forecast->date[0]->ws;



/* Die Ausgabe als Tabelle !!! 1 Tabelle für den Abfahrtsmonitor, 2 Tabelle für das Wetter !!!! 
Hier soll sich nun $position[1-18],$position1[1-18],$laufschrift,$uhrzeit,$datum in Tabelle 1 und in den verschiedenen zyklen aktualisieren
in Tabelle 2 sollen sich nun auch die Wetterdaten jede 1h aktualisieren !!!
*/
echo"
<div style='float: left; width: 50%;'>
<table width='100%' height='100%' bordercolor='black' cellpadding='0' cellspacing='0' style='font-family:Arial; font-weight:bolder'>
 <tr style='background-color:#FFCC00; font-family:Arial; font-weight:bold; font-size:80px'>
  <td  colspan='3' style='padding-top:30px; padding-bottom:30px ' ><img src='Datein/haltestellensymbol.png' alt='' border='0' width='70' height='70' style='float:left; margin-right:20px; margin-top:0px; margin-left:20px'>Lene-Glatzer Stra&szlig;e</td>
 <td  colspan='3' style='padding-top:30px; padding-bottom:30px ' ><img src='Datein/haltestellensymbol.png' alt='' border='0' width='70' height='70' style='float:left; margin-right:20px; margin-top:0px; margin-left:0px'>Jacobi-Stra&szlig;e</td>
 </tr>
 <tr style='font-size:40px; color:#FFFFFF' bgcolor='221300'>
  <td align='left' style='padding-left:20px' >Linie</td>
  <td align='left' style='padding-left:20px' >Richtung</td>
  <td align='center'>In Min</td>
  <td align='left' style='padding-left:20px'>Linie</td>
  <td align='left' style='padding-left:20px' >Richtung</td>
  <td align='right' >In Min</td>
 </tr>
  <tr style='font-size:80px; color:#E98F14' bgcolor='221300'>
  <td>$position[1]</td>
  <td>$position[2]</td>
  <td align='center'>$position[3]</td>
  <td class='sebastian'>$position1[1]</td>
  <td>$position1[2]</td>
  <td align='right'>$position1[3]</td>
 </tr>
  <tr style='font-size:80px; color:#E98F14' bgcolor='221300'>
  <td>$position[4]</td>
  <td>$position[5]</td>
  <td align='center'>$position[6]</td>
  <td class='sebastian'>$position1[4]</td>
  <td>$position1[5]</td>
  <td align='right'>$position1[6]</td>
 </tr>
  <tr style='font-size:80px; color:#E98F14' bgcolor='221300'>
  <td>$position[7]</td>
  <td>$position[8]</td>
  <td align='center'>$position[9]</td>
  <td class='sebastian'>$position1[7]</td>
  <td>$position1[8]</td>
  <td align='right'>$position1[9]</td>
 </tr>
  <tr style='font-size:80px; color:#E98F14' bgcolor='221300'>
  <td>$position[10]</td>
  <td>$position[11]</td>
  <td align='center'>$position[12]</td>
  <td class='sebastian'>$position1[10]</td>
  <td>$position1[11]</td>
  <td align='right' >$position1[12]</td>
 </tr>
  <tr style='font-size:80px; color:#E98F14' bgcolor='221300'>
  <td>$position[13]</td>
  <td>$position[14]</td>
  <td align='center'>$position[15]</td>
  <td class='sebastian'>$position1[13]</td>
  <td>$position1[14]</td>
  <td align='right'>$position1[15]</td>
 </tr>
  <tr style='font-size:80px; color:#E98F14' bgcolor='221300'>
  <td>$position[16]</td>
  <td>$position[17]</td>
  <td align='center'>$position[18]</td>
  <td class='sebastian'>$position1[16]</td>
  <td>$position1[17]</td>
  <td align='right'>$position1[18]</td>
 </tr>
 <tr style='font-size:80px; color:#E98F14' bgcolor='221300'>
  <td colspan='6'><marquee scrollamount='10' scrolldelay='10'>$laufschrift</marquee></td>
 </tr>
 <tr style='background-color:#FFCC00; font-family:Arial; font-size:80px;  font-weight:bolder' >
  <td colspan='2' style='padding-top:30px; padding-bottom:30px ' ><img src='Datein/uhr.png' alt='' border='0' width='62' height='70' style='float:left; margin-right:20px; margin-top:0px; margin-left:40px' >$uhrzeit</td>
  <td colspan='3' style='padding-top:30px; padding-bottom:30px ' ><img src='Datein/kalender.png' alt='' border='0' width='70' height='72' style='float:left; margin-right:20px; margin-top:0px; margin-left:0px' >$datum</td>
  <td></td>
 </tr>
</table>
</div>

<div bgcolor='yelow' height='100%' style='float: right; width: 50%;'>
      <table width='100%' cellpadding='10' cellspacing='10'>
                        <tr>
                         <td></td>
                        </tr>
                        <tr>
                         <td>
			  <div class='wetterbox'>
				<p class='schrift'>Früh</p>
				<img class='innenformatierungbild' title='$wetter_text' src='wettersymbolezahlen/$wetter_heute_frueh.svg'>
				<p class='innenwettertext'>$wetter_text_frueh</p>
				<img class='tempsymmax' title='$wetter_text' src='wettersymbolezahlen/max_temp.svg'>
				<p class='temp' > $max_temp_heute_frueh | $min_temp_heute_frueh &deg;C</p>
				<p class='niederschlag'>Niederschlagswahrscheinlichkeit: $niederschlags_ws_heute %</p>
				<p class='wind'>Windgeschwindigkeit: $wind_geschwindigkeit_heute km/h</p>
			  </div>
			 </td>
			 <td>
			  <div class='wetterbox'>
				<p class='schrift'>Mittag</p>
				<img class='innenformatierungbild' title='$wetter_text' src='wettersymbolezahlen/$wetter_heute_mittag.svg'>
				<p class='innenwettertext'>$wetter_text_mittag</p>
				<img class='tempsymmax' title='$wetter_text' src='wettersymbolezahlen/max_temp.svg'>
				<p class='temp' > $max_temp_heute_mittag | $min_temp_heute_mittag &deg;C</p>
				<p class='niederschlag'>Niederschlagswahrscheinlichkeit: $niederschlags_ws_heute %</p>
				<p class='wind'>Windgeschwindigkeit: $wind_geschwindigkeit_heute km/h</p>
			  </div>
			 </td>
			</tr>
			<tr>
			  <td>
			   <div class='wetterbox'>
				<p class='schrift'>Abend</p>
				<img class='innenformatierungbild' title='$wetter_text' src='wettersymbolezahlen/$wetter_heute_abend.svg'>
				<p class='innenwettertext'>$wetter_text_abend</p>
				<img class='tempsymmax' title='$wetter_text' src='wettersymbolezahlen/max_temp.svg'>
				<p class='temp' > $max_temp_heute_abend | $min_temp_heute_abend &deg;C</p>
				<p class='niederschlag'>Niederschlagswahrscheinlichkeit: $niederschlags_ws_heute %</p>
				<p class='wind'>Windgeschwindigkeit: $wind_geschwindigkeit_heute km/h</p>
			  </div>
			 </td>
			 <td>
			  <div class='wetterbox'>
				<p class='schrift'>Nacht</p>
				<img class='innenformatierungbild' title='$wetter_text' src='wettersymbolezahlen/$wetter_heute_nacht.svg'>
				<p class='innenwettertext'>$wetter_text_nacht</p>
				<img class='tempsymmax' title='$wetter_text' src='wettersymbolezahlen/max_temp.svg'>
				<p class='temp'> $max_temp_heute_nacht | $min_temp_heute_nacht &deg;C</p>
				<p class='niederschlag'>Niederschlagswahrscheinlichkeit: $niederschlags_ws_heute %</p>
				<p class='wind'>Windgeschwindigkeit: $wind_geschwindigkeit_heute km/h</p>
			  </div>
			 </td>
                        </tr>
                       </table>
</div>";
?>

</body>
</html>
