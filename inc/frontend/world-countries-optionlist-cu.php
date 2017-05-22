<?php 
global $current_user;

$curauth = $current_user;


$location = get_user_meta($curauth->ID, "_user_location", true);
$country = (isset($location['country'])) ? $location['country'] : 'USA';
?>
<option value="">Country..</option>
<option value="AFG" <?= ($country=="AFG") ? 'selected="selected"' : '' ?>>Afghanistan</option>
<option value="ALA" <?= ($country=="ALA") ? 'selected="selected"' : '' ?>>Åland Islands</option>
<option value="ALB" <?= ($country=="ALB") ? 'selected="selected"' : '' ?>>Albania</option>
<option value="DZA" <?= ($country=="DZA") ? 'selected="selected"' : '' ?>>Algeria</option>
<option value="ASM" <?= ($country=="ASM") ? 'selected="selected"' : '' ?>>American Samoa</option>
<option value="AND" <?= ($country=="AND") ? 'selected="selected"' : '' ?>>Andorra</option>
<option value="AGO" <?= ($country=="AGO") ? 'selected="selected"' : '' ?>>Angola</option>
<option value="AIA" <?= ($country=="AIA") ? 'selected="selected"' : '' ?>>Anguilla</option>
<option value="ATA" <?= ($country=="ATA") ? 'selected="selected"' : '' ?>>Antarctica</option>
<option value="ATG" <?= ($country=="ATG") ? 'selected="selected"' : '' ?>>Antigua and Barbuda</option>
<option value="ARG" <?= ($country=="ARG") ? 'selected="selected"' : '' ?>>Argentina</option>
<option value="ARM" <?= ($country=="ARM") ? 'selected="selected"' : '' ?>>Armenia</option>
<option value="ABW" <?= ($country=="ABW") ? 'selected="selected"' : '' ?>>Aruba</option>
<option value="AUS" <?= ($country=="AUS") ? 'selected="selected"' : '' ?>>Australia</option>
<option value="AUT" <?= ($country=="AUT") ? 'selected="selected"' : '' ?>>Austria</option>
<option value="AZE" <?= ($country=="AZE") ? 'selected="selected"' : '' ?>>Azerbaijan</option>
<option value="BHS" <?= ($country=="BHS") ? 'selected="selected"' : '' ?>>Bahamas</option>
<option value="BHR" <?= ($country=="BHR") ? 'selected="selected"' : '' ?>>Bahrain</option>
<option value="BGD" <?= ($country=="BGD") ? 'selected="selected"' : '' ?>>Bangladesh</option>
<option value="BRB" <?= ($country=="BRB") ? 'selected="selected"' : '' ?>>Barbados</option>
<option value="BLR" <?= ($country=="BLR") ? 'selected="selected"' : '' ?>>Belarus</option>
<option value="BEL" <?= ($country=="BEL") ? 'selected="selected"' : '' ?>>Belgium</option>
<option value="BLZ" <?= ($country=="BLZ") ? 'selected="selected"' : '' ?>>Belize</option>
<option value="BEN" <?= ($country=="BEN") ? 'selected="selected"' : '' ?>>Benin</option>
<option value="BMU" <?= ($country=="BMU") ? 'selected="selected"' : '' ?>>Bermuda</option>
<option value="BTN" <?= ($country=="BTN") ? 'selected="selected"' : '' ?>>Bhutan</option>
<option value="BOL" <?= ($country=="BOL") ? 'selected="selected"' : '' ?>>Bolivia, Plurinational State of</option>
<option value="BES" <?= ($country=="BES") ? 'selected="selected"' : '' ?>>Bonaire, Sint Eustatius and Saba</option>
<option value="BIH" <?= ($country=="BIH") ? 'selected="selected"' : '' ?>>Bosnia and Herzegovina</option>
<option value="BWA" <?= ($country=="BWA") ? 'selected="selected"' : '' ?>>Botswana</option>
<option value="BVT" <?= ($country=="BVT") ? 'selected="selected"' : '' ?>>Bouvet Island</option>
<option value="BRA" <?= ($country=="BRA") ? 'selected="selected"' : '' ?>>Brazil</option>
<option value="IOT" <?= ($country=="IOT") ? 'selected="selected"' : '' ?>>British Indian Ocean Territory</option>
<option value="BRN" <?= ($country=="BRN") ? 'selected="selected"' : '' ?>>Brunei Darussalam</option>
<option value="BGR" <?= ($country=="BGR") ? 'selected="selected"' : '' ?>>Bulgaria</option>
<option value="BFA" <?= ($country=="BFA") ? 'selected="selected"' : '' ?>>Burkina Faso</option>
<option value="BDI" <?= ($country=="BDI") ? 'selected="selected"' : '' ?>>Burundi</option>
<option value="KHM" <?= ($country=="KHM") ? 'selected="selected"' : '' ?>>Cambodia</option>
<option value="CMR" <?= ($country=="CMR") ? 'selected="selected"' : '' ?>>Cameroon</option>
<option value="CAN" <?= ($country=="CAN") ? 'selected="selected"' : '' ?>>Canada</option>
<option value="CPV" <?= ($country=="CPV") ? 'selected="selected"' : '' ?>>Cape Verde</option>
<option value="CYM" <?= ($country=="CYM") ? 'selected="selected"' : '' ?>>Cayman Islands</option>
<option value="CAF" <?= ($country=="CAF") ? 'selected="selected"' : '' ?>>Central African Republic</option>
<option value="TCD" <?= ($country=="TCD") ? 'selected="selected"' : '' ?>>Chad</option>
<option value="CHL" <?= ($country=="CHL") ? 'selected="selected"' : '' ?>>Chile</option>
<option value="CHN" <?= ($country=="CHN") ? 'selected="selected"' : '' ?>>China</option>
<option value="CXR" <?= ($country=="CXR") ? 'selected="selected"' : '' ?>>Christmas Island</option>
<option value="CCK" <?= ($country=="CCK") ? 'selected="selected"' : '' ?>>Cocos (Keeling) Islands</option>
<option value="COL" <?= ($country=="COL") ? 'selected="selected"' : '' ?>>Colombia</option>
<option value="COM" <?= ($country=="COM") ? 'selected="selected"' : '' ?>>Comoros</option>
<option value="COG" <?= ($country=="COG") ? 'selected="selected"' : '' ?>>Congo</option>
<option value="COD" <?= ($country=="COD") ? 'selected="selected"' : '' ?>>Congo, the Democratic Republic of the</option>
<option value="COK" <?= ($country=="COK") ? 'selected="selected"' : '' ?>>Cook Islands</option>
<option value="CRI" <?= ($country=="CRI") ? 'selected="selected"' : '' ?>>Costa Rica</option>
<option value="CIV" <?= ($country=="CIV") ? 'selected="selected"' : '' ?>>Côte d'Ivoire</option>
<option value="HRV" <?= ($country=="HRV") ? 'selected="selected"' : '' ?>>Croatia</option>
<option value="CUB" <?= ($country=="CUB") ? 'selected="selected"' : '' ?>>Cuba</option>
<option value="CUW" <?= ($country=="CUW") ? 'selected="selected"' : '' ?>>Curaçao</option>
<option value="CYP" <?= ($country=="CYP") ? 'selected="selected"' : '' ?>>Cyprus</option>
<option value="CZE" <?= ($country=="CZE") ? 'selected="selected"' : '' ?>>Czech Republic</option>
<option value="DNK" <?= ($country=="DNK") ? 'selected="selected"' : '' ?>>Denmark</option>
<option value="DJI" <?= ($country=="DJI") ? 'selected="selected"' : '' ?>>Djibouti</option>
<option value="DMA" <?= ($country=="DMA") ? 'selected="selected"' : '' ?>>Dominica</option>
<option value="DOM" <?= ($country=="DOM") ? 'selected="selected"' : '' ?>>Dominican Republic</option>
<option value="ECU" <?= ($country=="ECU") ? 'selected="selected"' : '' ?>>Ecuador</option>
<option value="EGY" <?= ($country=="EGY") ? 'selected="selected"' : '' ?>>Egypt</option>
<option value="SLV" <?= ($country=="SLV") ? 'selected="selected"' : '' ?>>El Salvador</option>
<option value="GNQ" <?= ($country=="GNQ") ? 'selected="selected"' : '' ?>>Equatorial Guinea</option>
<option value="ERI" <?= ($country=="ERI") ? 'selected="selected"' : '' ?>>Eritrea</option>
<option value="EST" <?= ($country=="EST") ? 'selected="selected"' : '' ?>>Estonia</option>
<option value="ETH" <?= ($country=="ETH") ? 'selected="selected"' : '' ?>>Ethiopia</option>
<option value="FLK" <?= ($country=="FLK") ? 'selected="selected"' : '' ?>>Falkland Islands (Malvinas)</option>
<option value="FRO" <?= ($country=="FRO") ? 'selected="selected"' : '' ?>>Faroe Islands</option>
<option value="FJI" <?= ($country=="FJI") ? 'selected="selected"' : '' ?>>Fiji</option>
<option value="FIN" <?= ($country=="FIN") ? 'selected="selected"' : '' ?>>Finland</option>
<option value="FRA" <?= ($country=="FRA") ? 'selected="selected"' : '' ?>>France</option>
<option value="GUF" <?= ($country=="GUF") ? 'selected="selected"' : '' ?>>French Guiana</option>
<option value="PYF" <?= ($country=="PYF") ? 'selected="selected"' : '' ?>>French Polynesia</option>
<option value="ATF" <?= ($country=="ATF") ? 'selected="selected"' : '' ?>>French Southern Territories</option>
<option value="GAB" <?= ($country=="GAB") ? 'selected="selected"' : '' ?>>Gabon</option>
<option value="GMB" <?= ($country=="GMB") ? 'selected="selected"' : '' ?>>Gambia</option>
<option value="GEO" <?= ($country=="GEO") ? 'selected="selected"' : '' ?>>Georgia</option>
<option value="DEU" <?= ($country=="DEU") ? 'selected="selected"' : '' ?>>Germany</option>
<option value="GHA" <?= ($country=="GHA") ? 'selected="selected"' : '' ?>>Ghana</option>
<option value="GIB" <?= ($country=="GIB") ? 'selected="selected"' : '' ?>>Gibraltar</option>
<option value="GRC" <?= ($country=="GRC") ? 'selected="selected"' : '' ?>>Greece</option>
<option value="GRL" <?= ($country=="GRL") ? 'selected="selected"' : '' ?>>Greenland</option>
<option value="GRD" <?= ($country=="GRD") ? 'selected="selected"' : '' ?>>Grenada</option>
<option value="GLP" <?= ($country=="GLP") ? 'selected="selected"' : '' ?>>Guadeloupe</option>
<option value="GUM" <?= ($country=="GUM") ? 'selected="selected"' : '' ?>>Guam</option>
<option value="GTM" <?= ($country=="GTM") ? 'selected="selected"' : '' ?>>Guatemala</option>
<option value="GGY" <?= ($country=="GGY") ? 'selected="selected"' : '' ?>>Guernsey</option>
<option value="GIN" <?= ($country=="GIN") ? 'selected="selected"' : '' ?>>Guinea</option>
<option value="GNB" <?= ($country=="GNB") ? 'selected="selected"' : '' ?>>Guinea-Bissau</option>
<option value="GUY" <?= ($country=="GUY") ? 'selected="selected"' : '' ?>>Guyana</option>
<option value="HTI" <?= ($country=="HTI") ? 'selected="selected"' : '' ?>>Haiti</option>
<option value="HMD" <?= ($country=="HMD") ? 'selected="selected"' : '' ?>>Heard Island and McDonald Islands</option>
<option value="VAT" <?= ($country=="VAT") ? 'selected="selected"' : '' ?>>Holy See (Vatican City State)</option>
<option value="HND" <?= ($country=="HND") ? 'selected="selected"' : '' ?>>Honduras</option>
<option value="HKG" <?= ($country=="HKG") ? 'selected="selected"' : '' ?>>Hong Kong</option>
<option value="HUN" <?= ($country=="HUN") ? 'selected="selected"' : '' ?>>Hungary</option>
<option value="ISL" <?= ($country=="ISL") ? 'selected="selected"' : '' ?>>Iceland</option>
<option value="IND" <?= ($country=="IND") ? 'selected="selected"' : '' ?>>India</option>
<option value="IDN" <?= ($country=="IDN") ? 'selected="selected"' : '' ?>>Indonesia</option>
<option value="IRN" <?= ($country=="IRN") ? 'selected="selected"' : '' ?>>Iran, Islamic Republic of</option>
<option value="IRQ" <?= ($country=="IRQ") ? 'selected="selected"' : '' ?>>Iraq</option>
<option value="IRL" <?= ($country=="IRL") ? 'selected="selected"' : '' ?>>Ireland</option>
<option value="IMN" <?= ($country=="IMN") ? 'selected="selected"' : '' ?>>Isle of Man</option>
<option value="ISR" <?= ($country=="ISR") ? 'selected="selected"' : '' ?>>Israel</option>
<option value="ITA" <?= ($country=="ITA") ? 'selected="selected"' : '' ?>>Italy</option>
<option value="JAM" <?= ($country=="JAM") ? 'selected="selected"' : '' ?>>Jamaica</option>
<option value="JPN" <?= ($country=="JPN") ? 'selected="selected"' : '' ?>>Japan</option>
<option value="JEY" <?= ($country=="JEY") ? 'selected="selected"' : '' ?>>Jersey</option>
<option value="JOR" <?= ($country=="JOR") ? 'selected="selected"' : '' ?>>Jordan</option>
<option value="KAZ" <?= ($country=="KAZ") ? 'selected="selected"' : '' ?>>Kazakhstan</option>
<option value="KEN" <?= ($country=="KEN") ? 'selected="selected"' : '' ?>>Kenya</option>
<option value="KIR" <?= ($country=="KIR") ? 'selected="selected"' : '' ?>>Kiribati</option>
<option value="PRK" <?= ($country=="PRK") ? 'selected="selected"' : '' ?>>Korea, Democratic People's Republic of</option>
<option value="KOR" <?= ($country=="KOR") ? 'selected="selected"' : '' ?>>Korea, Republic of</option>
<option value="KWT" <?= ($country=="KWT") ? 'selected="selected"' : '' ?>>Kuwait</option>
<option value="KGZ" <?= ($country=="KGZ") ? 'selected="selected"' : '' ?>>Kyrgyzstan</option>
<option value="LAO" <?= ($country=="LAO") ? 'selected="selected"' : '' ?>>Lao People's Democratic Republic</option>
<option value="LVA" <?= ($country=="LVA") ? 'selected="selected"' : '' ?>>Latvia</option>
<option value="LBN" <?= ($country=="LBN") ? 'selected="selected"' : '' ?>>Lebanon</option>
<option value="LSO" <?= ($country=="LSO") ? 'selected="selected"' : '' ?>>Lesotho</option>
<option value="LBR" <?= ($country=="LBR") ? 'selected="selected"' : '' ?>>Liberia</option>
<option value="LBY" <?= ($country=="LBY") ? 'selected="selected"' : '' ?>>Libya</option>
<option value="LIE" <?= ($country=="LIE") ? 'selected="selected"' : '' ?>>Liechtenstein</option>
<option value="LTU" <?= ($country=="LTU") ? 'selected="selected"' : '' ?>>Lithuania</option>
<option value="LUX" <?= ($country=="LUX") ? 'selected="selected"' : '' ?>>Luxembourg</option>
<option value="MAC" <?= ($country=="MAC") ? 'selected="selected"' : '' ?>>Macao</option>
<option value="MKD" <?= ($country=="MKD") ? 'selected="selected"' : '' ?>>Macedonia, the former Yugoslav Republic of</option>
<option value="MDG" <?= ($country=="MDG") ? 'selected="selected"' : '' ?>>Madagascar</option>
<option value="MWI" <?= ($country=="MWI") ? 'selected="selected"' : '' ?>>Malawi</option>
<option value="MYS" <?= ($country=="MYS") ? 'selected="selected"' : '' ?>>Malaysia</option>
<option value="MDV" <?= ($country=="MDV") ? 'selected="selected"' : '' ?>>Maldives</option>
<option value="MLI" <?= ($country=="MLI") ? 'selected="selected"' : '' ?>>Mali</option>
<option value="MLT" <?= ($country=="MLT") ? 'selected="selected"' : '' ?>>Malta</option>
<option value="MHL" <?= ($country=="MHL") ? 'selected="selected"' : '' ?>>Marshall Islands</option>
<option value="MTQ" <?= ($country=="MTQ") ? 'selected="selected"' : '' ?>>Martinique</option>
<option value="MRT" <?= ($country=="MRT") ? 'selected="selected"' : '' ?>>Mauritania</option>
<option value="MUS" <?= ($country=="MUS") ? 'selected="selected"' : '' ?>>Mauritius</option>
<option value="MYT" <?= ($country=="MYT") ? 'selected="selected"' : '' ?>>Mayotte</option>
<option value="MEX" <?= ($country=="MEX") ? 'selected="selected"' : '' ?>>Mexico</option>
<option value="FSM" <?= ($country=="FSM") ? 'selected="selected"' : '' ?>>Micronesia, Federated States of</option>
<option value="MDA" <?= ($country=="MDA") ? 'selected="selected"' : '' ?>>Moldova, Republic of</option>
<option value="MCO" <?= ($country=="MCO") ? 'selected="selected"' : '' ?>>Monaco</option>
<option value="MNG" <?= ($country=="MNG") ? 'selected="selected"' : '' ?>>Mongolia</option>
<option value="MNE" <?= ($country=="MNE") ? 'selected="selected"' : '' ?>>Montenegro</option>
<option value="MSR" <?= ($country=="MSR") ? 'selected="selected"' : '' ?>>Montserrat</option>
<option value="MAR" <?= ($country=="MAR") ? 'selected="selected"' : '' ?>>Morocco</option>
<option value="MOZ" <?= ($country=="MOZ") ? 'selected="selected"' : '' ?>>Mozambique</option>
<option value="MMR" <?= ($country=="MMR") ? 'selected="selected"' : '' ?>>Myanmar</option>
<option value="NAM" <?= ($country=="NAM") ? 'selected="selected"' : '' ?>>Namibia</option>
<option value="NRU" <?= ($country=="NRU") ? 'selected="selected"' : '' ?>>Nauru</option>
<option value="NPL" <?= ($country=="NPL") ? 'selected="selected"' : '' ?>>Nepal</option>
<option value="NLD" <?= ($country=="NLD") ? 'selected="selected"' : '' ?>>Netherlands</option>
<option value="NCL" <?= ($country=="NCL") ? 'selected="selected"' : '' ?>>New Caledonia</option>
<option value="NZL" <?= ($country=="NZL") ? 'selected="selected"' : '' ?>>New Zealand</option>
<option value="NIC" <?= ($country=="NIC") ? 'selected="selected"' : '' ?>>Nicaragua</option>
<option value="NER" <?= ($country=="NER") ? 'selected="selected"' : '' ?>>Niger</option>
<option value="NGA" <?= ($country=="NGA") ? 'selected="selected"' : '' ?>>Nigeria</option>
<option value="NIU" <?= ($country=="NIU") ? 'selected="selected"' : '' ?>>Niue</option>
<option value="NFK" <?= ($country=="NFK") ? 'selected="selected"' : '' ?>>Norfolk Island</option>
<option value="MNP" <?= ($country=="MNP") ? 'selected="selected"' : '' ?>>Northern Mariana Islands</option>
<option value="NOR" <?= ($country=="NOR") ? 'selected="selected"' : '' ?>>Norway</option>
<option value="OMN" <?= ($country=="OMN") ? 'selected="selected"' : '' ?>>Oman</option>
<option value="PAK" <?= ($country=="PAK") ? 'selected="selected"' : '' ?>>Pakistan</option>
<option value="PLW" <?= ($country=="PLW") ? 'selected="selected"' : '' ?>>Palau</option>
<option value="PSE" <?= ($country=="PSE") ? 'selected="selected"' : '' ?>>Palestinian Territory, Occupied</option>
<option value="PAN" <?= ($country=="PAN") ? 'selected="selected"' : '' ?>>Panama</option>
<option value="PNG" <?= ($country=="PNG") ? 'selected="selected"' : '' ?>>Papua New Guinea</option>
<option value="PRY" <?= ($country=="PRY") ? 'selected="selected"' : '' ?>>Paraguay</option>
<option value="PER" <?= ($country=="PER") ? 'selected="selected"' : '' ?>>Peru</option>
<option value="PHL" <?= ($country=="PHL") ? 'selected="selected"' : '' ?>>Philippines</option>
<option value="PCN" <?= ($country=="PCN") ? 'selected="selected"' : '' ?>>Pitcairn</option>
<option value="POL" <?= ($country=="POL") ? 'selected="selected"' : '' ?>>Poland</option>
<option value="PRT" <?= ($country=="PRT") ? 'selected="selected"' : '' ?>>Portugal</option>
<option value="PRI" <?= ($country=="PRI") ? 'selected="selected"' : '' ?>>Puerto Rico</option>
<option value="QAT" <?= ($country=="QAT") ? 'selected="selected"' : '' ?>>Qatar</option>
<option value="REU" <?= ($country=="REU") ? 'selected="selected"' : '' ?>>Réunion</option>
<option value="ROU" <?= ($country=="ROU") ? 'selected="selected"' : '' ?>>Romania</option>
<option value="RUS" <?= ($country=="RUS") ? 'selected="selected"' : '' ?>>Russian Federation</option>
<option value="RWA" <?= ($country=="RWA") ? 'selected="selected"' : '' ?>>Rwanda</option>
<option value="BLM" <?= ($country=="BLM") ? 'selected="selected"' : '' ?>>Saint Barthélemy</option>
<option value="SHN" <?= ($country=="SHN") ? 'selected="selected"' : '' ?>>Saint Helena, Ascension and Tristan da Cunha</option>
<option value="KNA" <?= ($country=="KNA") ? 'selected="selected"' : '' ?>>Saint Kitts and Nevis</option>
<option value="LCA" <?= ($country=="LCA") ? 'selected="selected"' : '' ?>>Saint Lucia</option>
<option value="MAF" <?= ($country=="MAF") ? 'selected="selected"' : '' ?>>Saint Martin (French part)</option>
<option value="SPM" <?= ($country=="SPM") ? 'selected="selected"' : '' ?>>Saint Pierre and Miquelon</option>
<option value="VCT" <?= ($country=="VCT") ? 'selected="selected"' : '' ?>>Saint Vincent and the Grenadines</option>
<option value="WSM" <?= ($country=="WSM") ? 'selected="selected"' : '' ?>>Samoa</option>
<option value="SMR" <?= ($country=="SMR") ? 'selected="selected"' : '' ?>>San Marino</option>
<option value="STP" <?= ($country=="STP") ? 'selected="selected"' : '' ?>>Sao Tome and Principe</option>
<option value="SAU" <?= ($country=="SAU") ? 'selected="selected"' : '' ?>>Saudi Arabia</option>
<option value="SEN" <?= ($country=="SEN") ? 'selected="selected"' : '' ?>>Senegal</option>
<option value="SRB" <?= ($country=="SRB") ? 'selected="selected"' : '' ?>>Serbia</option>
<option value="SYC" <?= ($country=="SYC") ? 'selected="selected"' : '' ?>>Seychelles</option>
<option value="SLE" <?= ($country=="SLE") ? 'selected="selected"' : '' ?>>Sierra Leone</option>
<option value="SGP" <?= ($country=="SGP") ? 'selected="selected"' : '' ?>>Singapore</option>
<option value="SXM" <?= ($country=="SXM") ? 'selected="selected"' : '' ?>>Sint Maarten (Dutch part)</option>
<option value="SVK" <?= ($country=="SVK") ? 'selected="selected"' : '' ?>>Slovakia</option>
<option value="SVN" <?= ($country=="SVN") ? 'selected="selected"' : '' ?>>Slovenia</option>
<option value="SLB" <?= ($country=="SLB") ? 'selected="selected"' : '' ?>>Solomon Islands</option>
<option value="SOM" <?= ($country=="SOM") ? 'selected="selected"' : '' ?>>Somalia</option>
<option value="ZAF" <?= ($country=="ZAF") ? 'selected="selected"' : '' ?>>South Africa</option>
<option value="SGS" <?= ($country=="SGS") ? 'selected="selected"' : '' ?>>South Georgia and the South Sandwich Islands</option>
<option value="SSD" <?= ($country=="SSD") ? 'selected="selected"' : '' ?>>South Sudan</option>
<option value="ESP" <?= ($country=="ESP") ? 'selected="selected"' : '' ?>>Spain</option>
<option value="LKA" <?= ($country=="LKA") ? 'selected="selected"' : '' ?>>Sri Lanka</option>
<option value="SDN" <?= ($country=="SDN") ? 'selected="selected"' : '' ?>>Sudan</option>
<option value="SUR" <?= ($country=="SUR") ? 'selected="selected"' : '' ?>>Suriname</option>
<option value="SJM" <?= ($country=="SJM") ? 'selected="selected"' : '' ?>>Svalbard and Jan Mayen</option>
<option value="SWZ" <?= ($country=="SWZ") ? 'selected="selected"' : '' ?>>Swaziland</option>
<option value="SWE" <?= ($country=="SWE") ? 'selected="selected"' : '' ?>>Sweden</option>
<option value="CHE" <?= ($country=="CHE") ? 'selected="selected"' : '' ?>>Switzerland</option>
<option value="SYR" <?= ($country=="SYR") ? 'selected="selected"' : '' ?>>Syrian Arab Republic</option>
<option value="TWN" <?= ($country=="TWN") ? 'selected="selected"' : '' ?>>Taiwan, Province of China</option>
<option value="TJK" <?= ($country=="TJK") ? 'selected="selected"' : '' ?>>Tajikistan</option>
<option value="TZA" <?= ($country=="TZA") ? 'selected="selected"' : '' ?>>Tanzania, United Republic of</option>
<option value="THA" <?= ($country=="THA") ? 'selected="selected"' : '' ?>>Thailand</option>
<option value="TLS" <?= ($country=="TLS") ? 'selected="selected"' : '' ?>>Timor-Leste</option>
<option value="TGO" <?= ($country=="TGO") ? 'selected="selected"' : '' ?>>Togo</option>
<option value="TKL" <?= ($country=="TKL") ? 'selected="selected"' : '' ?>>Tokelau</option>
<option value="TON" <?= ($country=="TON") ? 'selected="selected"' : '' ?>>Tonga</option>
<option value="TTO" <?= ($country=="TTO") ? 'selected="selected"' : '' ?>>Trinidad and Tobago</option>
<option value="TUN" <?= ($country=="TUN") ? 'selected="selected"' : '' ?>>Tunisia</option>
<option value="TUR" <?= ($country=="TUR") ? 'selected="selected"' : '' ?>>Turkey</option>
<option value="TKM" <?= ($country=="TKM") ? 'selected="selected"' : '' ?>>Turkmenistan</option>
<option value="TCA" <?= ($country=="TCA") ? 'selected="selected"' : '' ?>>Turks and Caicos Islands</option>
<option value="TUV" <?= ($country=="TUV") ? 'selected="selected"' : '' ?>>Tuvalu</option>
<option value="UGA" <?= ($country=="UGA") ? 'selected="selected"' : '' ?>>Uganda</option>
<option value="UKR" <?= ($country=="UKR") ? 'selected="selected"' : '' ?>>Ukraine</option>
<option value="ARE" <?= ($country=="ARE") ? 'selected="selected"' : '' ?>>United Arab Emirates</option>
<option value="GBR" <?= ($country=="GBR") ? 'selected="selected"' : '' ?>>United Kingdom</option>
<option value="USA" <?= ($country=="USA") ? 'selected="selected"' : '' ?>>United States</option>
<option value="UMI" <?= ($country=="UMI") ? 'selected="selected"' : '' ?>>United States Minor Outlying Islands</option>
<option value="URY" <?= ($country=="URY") ? 'selected="selected"' : '' ?>>Uruguay</option>
<option value="UZB" <?= ($country=="UZB") ? 'selected="selected"' : '' ?>>Uzbekistan</option>
<option value="VUT" <?= ($country=="VUT") ? 'selected="selected"' : '' ?>>Vanuatu</option>
<option value="VEN" <?= ($country=="VEN") ? 'selected="selected"' : '' ?>>Venezuela, Bolivarian Republic of</option>
<option value="VNM" <?= ($country=="VNM") ? 'selected="selected"' : '' ?>>Viet Nam</option>
<option value="VGB" <?= ($country=="VGB") ? 'selected="selected"' : '' ?>>Virgin Islands, British</option>
<option value="VIR" <?= ($country=="VIR") ? 'selected="selected"' : '' ?>>Virgin Islands, U.S.</option>
<option value="WLF" <?= ($country=="WLF") ? 'selected="selected"' : '' ?>>Wallis and Futuna</option>
<option value="ESH" <?= ($country=="ESH") ? 'selected="selected"' : '' ?>>Western Sahara</option>
<option value="YEM" <?= ($country=="YEM") ? 'selected="selected"' : '' ?>>Yemen</option>
<option value="ZMB" <?= ($country=="ZMB") ? 'selected="selected"' : '' ?>>Zambia</option>
<option value="ZWE" <?= ($country=="ZWE") ? 'selected="selected"' : '' ?>>Zimbabwe</option>