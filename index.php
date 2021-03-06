<?php 
    session_start();
    if(isset($_GET['logout']))
    {
        unset($_SESSION["user"], $_SESSION["user_full_name"], $_SESSION["sess"], $_SESSION['timestamp']);
        
    }
   
    if(!isset($_SESSION['user']) && !isset($_SESSION['sess']))
    {
        $destination = 'src/login.php';
       header("Location: ".$destination);
       exit;
    }

    $user = (isset($_SESSION['user_full_name']))?$_SESSION['user_full_name']:'Uknown';

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Victim Services Of Brant - CDS</title>

    <link rel="apple-touch-icon" sizes="57x57" href="img/fav-icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/fav-icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/fav-icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/fav-icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/fav-icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/fav-icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/fav-icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/fav-icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="img/fav-icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="img/fav-icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/fav-icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/fav-icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/fav-icon/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">




    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="css/chosen.min.css">
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/reports.css" rel="stylesheet">
</head>

<body>
    <div id="messageContainer"></div>

    <div class="topHeader">
        <div class="row headerInnerContainer" style="padding: 10px 0px;">
            <div class="col-md-3 logo">
                <img style="width: 150px; margin-right:10px;" src="img/vslogo.png" alt="">
                <h5 style="padding-top: 50px; font-size:15pt;">Court <br/>Data<br/> System</h5>
            </div>
            <div class="col-md-9">
                <a class="logoutBtn" href="index.php?logout=true">Sign Out <i class="glyphicon glyphicon-off"></i></a>
            </div>

        </div>


    </div>

    <!-- SUB HEADER -->
    <div class="header" style="position: relative;">
        <div class="row headerInnerContainer">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" class="btn ux-btn-default" id="clientsBtn">Clients</button>
                            <button type="button" class="btn ux-btn-default" id="splitBtn">split</button>
                            <button type="button" class="btn ux-btn-default" id="reportsBtn">Reports</button>
                        </div>

                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="row">

                    <div class="col-md-8" style="border-right: 1px solid silver;">
                        <div class="pull-right">
                            <p><small>Current Time</small></p>
                            <h4 id="time"></h4>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="pull-right">
                            <button style="margin-top:10%;" id="addBtn" data-toggle="modal" data-target="#mainForm" type="button" class="btn btn-default">Create new client</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- END SUB HEADER -->


    <!-- MODAL -->
    <div class="modal fade" id="mainForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="action-btn-wrapper">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active main"><a href="#client" class="clientIdBtn" aria-controls="client" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i>  Client: <span class="cidtab"></span></a></li>
                            <li role="presentation"><a href="#children" aria-controls="children" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-baby-formula"></i>  Children</a></li>
                            <li role="presentation" class="accusedTab"><a href="#add-accused" aria-controls="add-accused" role="tab" data-toggle="tab"><small><i class="glyphicon glyphicon-hand-right"></i></small><i class="glyphicon glyphicon-user"></i>  Accused</a></li>

                            <li role="presentation"><a href="#notes" aria-controls="notes" role="tab" data-toggle="tab"><i class="fa fa-sticky-note"></i>  Notes & Documents</a></li>
                            <li role="presentation" class="callsTab"><a href="#calls" aria-controls="calls" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-phone-alt"></i>  Calls</a></li>
                        </ul>
                    </div>
                    <!-- end action-btn-wrapper -->

                </div>
                <div class="modal-body" id="modal-body">

                    <!-- MAIN FORM -->
                    <form id="main-form" enctype="multipart/form-data">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="client">
                                <fieldset>
                                    <div class="clientSafetyContainer">
                                        <div class="client_safety_concern">
                                            <input type="checkbox" value="0" id="client_safety_concern" name="client_safety_concern" />
                                            <label for="client_safety_concern"><i class="glyphicon glyphicon-alert"></i></label>
                                        </div>
                                        <p class="client_safety_label" style="text-align: center;text-transform: uppercase;line-height:1; font-size:8pt;margin-top:30px !important;"><small>safety<br/> concern</small></p>
                                    </div>
                                    <div class="clientHistoricalContainer">
                                        <div class="client_historical">
                                            <input type="checkbox" value="0" id="client_historical" name="client_historical" />
                                            <label for="client_historical"><i class="fa fa-book" aria-hidden="true"></i></label>
                                        </div>
                                        <p class="client_safety_label" style="text-align: center;text-transform: uppercase;line-height:1; font-size:8pt;margin-top:30px !important;"><small>HISTORICAL</small></p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2>Client <i class="fa fa-id-card-o" aria-hidden="true"></i> :
                                                <a href="#" class="client_id"></a>
                                            </h2>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row input-row" style="margin-top:20px !important;">

                                                <div class="col-md-3 text-input" style="margin-right:15px !important;border-right:dotted 1px silver;">
                                                    <div class="client_domestic_violence">
                                                        <input type="checkbox" value="0" id="client_domestic_violence" name="client_domestic_violence" />
                                                        <label for="client_domestic_violence"></label>
                                                    </div>
                                                    <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>Domestic Violence</small></p>

                                                </div>
                                                <div class="col-md-3 text-input" style="margin-right:15px !important;border-right:dotted 1px silver;">
                                                    <div class="client_human_traf">
                                                        <input type="checkbox" value="0" id="client_human_traf" name="client_human_traf" />
                                                        <label for="client_human_traf"></label>
                                                    </div>
                                                    <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>Human Trafficking</small></p>
                                                </div>
                                                <div class="col-md-3 text-input">
                                                    <div class="client_elder_abuse">
                                                        <input type="checkbox" value="0" id="client_elder_abuse" name="client_elder_abuse" />
                                                        <label for="client_elder_abuse"></label>
                                                    </div>
                                                    <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>Elder Abuse</small></p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="row clientSectionParent">
                                        <div class="col-md-6 clientSection1">

                                            <div class="row input-row">
                                                <div class="col-md-2 input-p">
                                                    <p><span style="color: red;">*</span> Name: </p>
                                                </div>
                                                <div class="col-md-4 text-input"><input style="width: 95% !important;" placeholder="First Name" required type="text" name="client_name" id="client_name"></div>
                                                <div class="col-md-4 text-input"><input style="width: 95% !important;" placeholder="Surname" required type="text" name="client_surname" id="client_surname"></div>
                                                <div class="col-md-2 text-input">
                                                    <select name="client_gender" id="client_gender">
                                                    <option selected value="Male">Male</option>
                                                    <option value="Female">Fem</option>
                                                    <option value="Transgender">Trans</option>
                                                </select>


                                                </div>


                                            </div>
                                            <div class="row input-row">
                                                <div class="col-md-2 input-p">
                                                    <p>Address: </p>
                                                </div>
                                                <div class="col-md-10 text-input"><input placeholder="123 victim st" style="width:100% !important;" type="text" name="client_address" id="client_address"></div>
                                            </div>
                                            <div class="row input-row">
                                                <div class="col-md-2 input-p">

                                                </div>
                                                <div class="col-md-5 text-input"><input style="width: 95% !important;" placeholder="City" type="text" name="client_city" id="client_city"></div>
                                                <div class="col-md-5 text-input"><input style="width: 100% !important;" placeholder="Postal Code" type="text" name="client_postal" id="client_postal"></div>
                                            </div>
                                            <div class="row input-row">
                                                <div class="col-md-2 input-p">
                                                    <p>D.O.B</p>
                                                </div>
                                                <div class="col-md-5 text-input"><input style="width:95% !important;" type="date" name="client_dob" id="client_dob" value=""></div>

                                                <div class="col-md-5 text-input">
                                                    <select style="width:100% !important;" name="client_age_range" id="client_age_range">
                                                    <option selected>Age Range...</option>
                                                    <option value="Under 16">Age Range: Under 16</option>
                                                    <option value="16-19">Age Range: 16-19</option>
                                                    <option value="20-25">Age Range: 20-25</option>
                                                    <option value="26-45">Age Range: 26-45</option>
                                                    <option value="46-65">Age Range: 46-65</option>
                                                    <option value="65+">Age Range: 65+</option>
                                                </select>
                                                </div>

                                            </div>


                                            <div class="row input-row">
                                                <div class="col-md-2 input-p">
                                                    <p>Phone Number: </p>
                                                </div>
                                                <div class="col-md-10 text-input"><input pattern="/^\d{10}$/" type="text" name="client_home_phone" id="client_home_phone"></div>
                                            </div>
                                            <div class="row input-row">
                                                <div class="col-md-2 input-p">
                                                    <p>ALT-Phone Number: </p>
                                                </div>
                                                <div class="col-md-10 text-input"><input pattern="/^\d{10}$/" type="text" name="client_alt_phone" id="client_alt_phone"></div>
                                            </div>
                                            <div class="row input-row">
                                                <div class="col-md-2 input-p">
                                                    <p>Email: </p>
                                                </div>
                                                <div class="col-md-10 text-input"><input type="email" name="client_email" id="client_email"></div>
                                            </div>
                                            <div class="row input-row">
                                                <div class="col-md-4 text-input">
                                                    <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin:20px 0px !important;"><small>Indigenous</small></p>
                                                    <div class="clientIndigenousCheck">
                                                        <input type="checkbox" value="0" name="client_indigenous" id="client_indigenous" />
                                                        <label for="client_indigenous"></label>
                                                    </div>

                                                </div>


                                                <div class="col-md-4 text-input">
                                                    <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin:20px 0px !important;"><small>Court Assisted</small></p>
                                                    <div class="client_court_assisted">
                                                        <input type="checkbox" value="0" id="client_court_assisted" name="client_court_assisted" />
                                                        <label for="client_court_assisted"></label>
                                                    </div>

                                                </div>
                                                <div class="col-md-4 text-input">
                                                    <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin:20px 0px !important;"><small>vwap faxed</small></p>
                                                    <div class="vwapCheck">
                                                        <input type="checkbox" value="0" name="client_vwap" id="client_vwap" />
                                                        <label for="client_vwap"></label>
                                                    </div>

                                                </div>
                                            </div>


                                        </div>

                                        <div class="col-md-6 clientSection2">

                                            <div class="row input-row">
                                                <div class="col-md-4 input-p">
                                                    <p><span style="color: red;">*</span>Incident#: </p>
                                                </div>
                                                <div class="col-md-4 text-input"><input style="width: 90% !important;" required type="text" id="police_incident_id" name="police_incident_id"></div>
                                                <div class="col-md-4 text-input">
                                                    <select required style="width: 82% !important;" name="client_referred_by" id="client_referred_by">
                                                    <option value="BPS">BPS</option>
                                                    <option value="BPS WASH">BPS WASH</option>
                                                    <option value="OPP">OPP</option>
                                                    <option value="OPP WASH">OPP WASH</option>
                                                    <option value="SNP">SNP</option>
                                                    <option value="SNP WASH">SNP WASH</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                </div>
                                            </div>


                                            <div class="row input-row">
                                                <div class="col-md-4 input-p">
                                                    <p>Referrals Provided: </p>
                                                </div>
                                                <div class="col-md-8 text-input">
                                                    <select multiple data-placeholder="Select referrals" name="client_referral_provided[]" id="client_referral_provided">
                                                    <option value="VWAP">VWAP</option>
                                                    <option value="BFACS">BFACS</option>
                                                    <option value="CIC">CIC</option>
                                                    <option value="Ganhohkwasra">Ganhohkwasra</option>
                                                    <option value="Legal Aid">Legal Aid</option>
                                                    <option value="Nova Vita">Nova Vita</option>
                                                    <option value="Ontario Works">Ontario Works</option>
                                                    <option value="Restitution">Restitution</option>
                                                    <option value="SAC">SAC</option>
                                                    <option value="Safety Plan">Safety Plan</option>
                                                    <option value="St.Leonards">St.Leonards</option>
                                                    <option value="VIS">VIS</option>
                                                    <option value="Victim Support Line">Victim Support Line</option>
                                                    <option value="VQRP">VQRP</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="row input-row">
                                                <div class="col-md-4 input-p">
                                                    <p>Date of Offence: </p>
                                                </div>
                                                <div class="col-md-8 text-input"><input id="client_date_of_offence" name="client_date_of_offence" type="date"></div>

                                            </div>
                                            <div class="row input-row">
                                                <div class="col-md-4 input-p">
                                                    <p>Intake Date: </p>
                                                </div>
                                                <div class="col-md-8 text-input"><input id="client_intake_date" name="client_intake_date" type="date"></div>

                                            </div>

                                            <div class="row input-row">
                                                <div class="col-md-4 input-p">
                                                    <p>Charges: </p>
                                                </div>
                                                <div class="col-md-8 text-input">
                                                    <select data-placeholder="Select charges" name="client_charges[]" id="client_charges" multiple="multiple">
                                                        <option value="Accident cause fatality">Accident cause fatality</option>
                                                        <option value="Aggravated assault">Aggravated assault</option>
                                                        <option value="Assault cause bandily harm">Assault cause bandily harm</option>
                                                        <option value="Assault with a weapon">Assault with a weapon</option>
                                                        <option value="Advertising sex service under 18">Advertising sex service under 18</option>
                                                        <option value="Assault">Assault</option>
                                                        <option value="Arson">Arson</option>
                                                        <option value="Breach court order">Breach court order</option>
                                                        <option value="Criminal Harasment">Criminal Harasment</option>
                                                        <option value="Disguise with intent">Disguise with intent</option>
                                                        <option value="Exceed 80 milligrams">Exceed 80 milligrams</option>
                                                        <option value="Forcible confinement">Forcible confinement</option>
                                                        <option value="Fraud/Theft">Fraud/Theft</option>
                                                        <option value="Firearm in motor vehicle">Firearm in motor vehicle</option>
                                                        <option value="Human trafficking">Human trafficking</option>
                                                        <option value="Impaired causing death">Impaired causing death</option>
                                                        <option value="Intimidation">Intimidation</option>
                                                        <option value="Mischief">Mischief</option>
                                                        <option value="Murder/Attempt Murder/Manslaughter">Murder/Attempt Murder/Manslaughter</option>
                                                        <option value="Mischief/Cause disturbance">Mischief/Cause disturbance</option>
                                                        <option value="Making child pornography">Making child pornography</option>
                                                        <option value="Other">Other</option>
                                                        <option value="Possession child pornography">Possession child pornography</option>
                                                        <option value="Procuring trafficking persons">Procuring trafficking persons</option>
                                                        <option value="Point firearm">Point firearm</option>
                                                        <option value="Possession of firearm">Possession of firearm</option>
                                                        <option value="Procuring under 18">Procuring under 18</option>
                                                        <option value="Possession of a weapon">Possession of a weapon</option>
                                                        <option value="Robbery/Break and enter">Robbery/Break and enter</option>
                                                        <option value="Robbery with firearm">Robbery with firearm</option>
                                                        <option value="Robbery with weapon">Robbery with weapon</option>
                                                        <option value="Strangulation">Strangulation</option>
                                                        <option value="Sexual assault">Sexual assault</option>
                                                        <option value="Unauthorized possession of a firearm">Unauthorized possession of a firearm</option>
                                                        <option value="Unlawfully in a dwelling">Unlawfully in a dwelling</option>
                                                        <option value="Uttering Threats">Uttering Threats</option>
                                                        <option value="Voyeurism">Voyeurism</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                </fieldset>

                                </div>

                                <div role="tabpanel" class="tab-pane" id="children">
                                    <fieldset>
                                        <h2>Children <i class="fa fa-child" aria-hidden="true"></i></h2>
                                        <div class="row childrenSectionParent">

                                            <div class="col-md-6 childrenSection1">
                                                <div class="row input-row">
                                                    <div class="col-md-2 input-p">
                                                        <p>Children Names: </p>
                                                    </div>
                                                    <div class="col-md-10 text-input"><textarea rows="3" name="children_names" id="children_names"></textarea></div>
                                                </div>
                                                <div class="row input-row">
                                                    <div class="col-md-2 input-p">
                                                        <p>Select: </p>
                                                    </div>
                                                    <div class="col-md-10 text-input">
                                                        <select name="children_outcome" id="children_outcome">
                                                    <option value="Child Victim">Child Victim</option>
                                                    <option value="Witness/Heard">Witness/Heard</option>
                                                </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 childrenSection2">
                                                <div class="row input-row">
                                                    <div class="col-md-4 input-p">
                                                        <p>CAS Notified Date: </p>
                                                    </div>
                                                    <div class="col-md-8 text-input"><input type="date" name="children_cas_notified_date" id="children_cas_notified_date"></div>
                                                </div>
                                                <div class="row input-row">
                                                    <div class="col-md-4 input-p">
                                                        <p>CAS Worker: </p>
                                                    </div>
                                                    <div class="col-md-8 text-input"><input type="text" name="children_cas_worker" id="children_cas_worker"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>


                                </div>




                                <div role="tabpanel" class="tab-pane accused-tab-pane" id="add-accused">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h2>Accused <i class="fa fa-id-card" aria-hidden="true"></i></h2>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" id="addAccusedBtn">Add Accused</button>
                                        </div>
                                    </div>
                                    <div class="accusedMainContainer row"></div>

                                </div>


                                <div role="tabpanel" class="tab-pane" id="notes">
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h2>Notes __<i class="glyphicon glyphicon-pencil"></i></h2>
                                            </div>
                                            <div class="col-md-6">
                                                <h2>Documents <i class="fa fa-file-text" aria-hidden="true"></i></h2>
                                            </div>
                                        </div>

                                        <div class="row notesDocsContainer">
                                            <div class="col-md-6 notesSection">
                                                <div class="row notesSectionParent">
                                                    <textarea rows="10" cols="50" name="client_notes" id="client_notes"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6 docSection">

                                                <div class="row" style="border-bottom: dotted 1px rgba(44,61,81,.3) !important; padding: 15px;">
                                                    <div class="col-md-12 documents">
                                                        <h4>Upload</h4>
                                                        <ul>
                                                            <li>Up to 20 files can be uploaded simultaniously</li>
                                                            <li>Each files should be no more than 50KB</li>
                                                            <li>Combined total should not exceed 8MB</li>
                                                        </ul>
                                                        <input style="border: none !important; margin-top:20px !important;" data-toggle="popover" data-placement="bottom" type="file" name="client_documents[]" id="client_documents" multiple>
                                                        <input type="hidden" name="MAX_FILE_SIZE" value="51200">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class=" col-md-12" style="padding: 20px;">
                                                        <h4>Files</h4>
                                                        <div class="row documentsList"></div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                    </fieldset>


                                </div>



                                <div role="tabpanel" class="tab-pane" id="calls">
                                    <fieldset>
                                        <div class="row callsTabHeader">
                                            <div class="col-md-3">
                                                <h3>Tel:
                                                    <spam style="font-size:15pt;" class="client-phone-number"></spam>
                                                </h3>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="row callsRadioContainer">
                                                            <div class="col-md-1">
                                                                <div class="squaredOne">
                                                                    <input type="radio" value="All" id="calls_all" name="calls_outcome_filter" checked />
                                                                    <label for="calls_all"></label>
                                                                </div>
                                                                <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>all</small></p>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="squaredOne">
                                                                    <input type="radio" value="Info Calls" id="calls_info_call" name="calls_outcome_filter" />
                                                                    <label for="calls_info_call"></label>
                                                                </div>
                                                                <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>Info Calls</small></p>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="squaredOne">
                                                                    <input type="radio" value="Initial Court Call" id="calls_initial_court_call" name="calls_outcome_filter" />
                                                                    <label for="calls_initial_court_call"></label>
                                                                </div>
                                                                <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>Initial Court Call</small></p>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="squaredOne">
                                                                    <input type="radio" value="Remand Call" id="calls_remand" name="calls_outcome_filter" />
                                                                    <label for="calls_remand"></label>
                                                                </div>
                                                                <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>Remand Call</small></p>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <!-- SEPARATOR -->
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="squaredOne">
                                                                    <input type="radio" value="Release/Detain Call" id="calls_release_detain" name="calls_outcome_filter" />
                                                                    <label for="calls_release_detain"></label>
                                                                </div>
                                                                <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>Release/ Detain Call</small></p>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="squaredOne">
                                                                    <input type="radio" value="Message Left" id="calls_message_left" name="calls_outcome_filter" />
                                                                    <label for="calls_message_left"></label>
                                                                </div>
                                                                <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>Message Left</small></p>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="squaredOne">
                                                                    <input type="radio" value="Letter Sent" id="calls_letter_sent" name="calls_outcome_filter" />
                                                                    <label for="calls_letter_sent"></label>
                                                                </div>
                                                                <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>Letter Sent</small></p>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="squaredOne">
                                                                    <input type="radio" value="No Contact" id="calls_no_contact" name="calls_outcome_filter" />
                                                                    <label for="calls_no_contact"></label>
                                                                </div>
                                                                <p style="text-transform: uppercase;line-height:1; font-size:8pt;margin-top:10px !important;"><small>No Contact</small></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="addCallBtn pull-right" id="addCallBtn" type="button">Add Call</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row calls">


                                        </div>




                                    </fieldset>

                                </div>

                            </div>

                            <input type="hidden" id="client_id" name="client_id" value="">


                    </form>
                    <!-- END MAIN FORM -->

                    <!-- CALLS MODAL -->
                    <div class="calls_modal">
                        <form id="calls-form">
                            <div class="row">
                                <div class="col-md-10">
                                    <h3>NEW CALL</h3>
                                </div>
                                <div class="col-md-2">
                                    <button class="callsModalCloseBtn" type="button">X</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <p style="white-space:nowrap;">DATE & TIME:</p>
                                </div>
                                <div class="col-md-8"><input type="text" required pattern="^(((((0?[13578])|(1[0-2]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|([1-2][0-9]))))[\-\/\s]?\d{4})(\s(((0?[1-9])|(1[0-2]))\:([0-5][0-9])((\s)|(\:([0-5][0-9])\s))([AM|PM|am|pm]{2,2})))?$"
                                        placeholder="Format: 02/17/2017 3:33 pm" name="call_date" id="call_date"></div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p>OUTCOME:</p>
                                </div>
                                <div class="col-md-8">

                                    <select name="call_outcome">
                                        <option value="Info Calls">Info Calls</option>
                                        <option value="Initial Court Call">Initial Court Call</option>
                                        <option value="Remand Call">Remand Call</option>
                                        <option value="Release/Detain Call">Release/Detain Call</option>
                                        <option value="Message Left">Message Left</option>
                                        <option value="Letter Sent">Letter Sent</option>
                                        <option value="No Contact">No Contact</option>
                                    </select>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12"><textarea rows="5" placeholder="Notes..." name="call_notes" id=""></textarea></div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="callsModalSaveBtn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- END CALLS MODAL -->

                    <!--ACUSSED MODAL -->
                    <div class="accusedModal">
                        <form id="accusedForm">
                            <div class="row accuedModalRow">
                                <div class="col-md-10">
                                    <h3>Accused ID :
                                        <a href="#" class="accused_id"></a>
                                    </h3>
                                </div>
                                <div class="col-md-2"><button class="accusedModalCloseBtn" type="button">X</button></div>

                            </div>
                            <div class="row accuedModalRow">
                                <div class="col-md-2">
                                    <p>Name: </p>
                                </div>
                                <div class="col-md-4"><input type="text" name="accused_surname" value="" id="accused_surname" placeholder="Accused First Name"></div>
                                <div class="col-md-4"><input type="text" name="accused_name" value="" id="accused_name" placeholder="Accused Surname"></div>
                                <div class="col-md-2">
                                    <select name="accused_gender" id="accused_gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Transgender">Transgender</option>
                                </select>
                                </div>
                            </div>

                            <div class="row accuedModalRow">
                                <div class="col-md-6">D.O.B: </div>
                                <div class="col-md-6">
                                    <input type="date" name="accused_dob" id="accused_dob">
                                </div>
                            </div>

                            <div class="row accuedModalRow">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p>Indigenous: </p>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="accussedIndigenousCheck">
                                                <input type="checkbox" value="0" id="accused_indigenous" name="accused_indigenous" />
                                                <label for="accused_indigenous"></label>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>Young Offender: </p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="youngOffenderCheck">
                                                <input type="checkbox" value="0" id="accused_young_offender" name="accused_young_offender" />
                                                <label for="accused_young_offender"></label>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="row accuedModalRow">
                                <div class="col-md-2">
                                    <p>Order 516: </p>
                                </div>
                                <div class="col-md-10">
                                    <div class="accused_516">
                                        <input type="checkbox" value="0" id="accused_516" name="accused_516" />
                                        <label for="accused_516"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row accuedFooter">
                                <div class="col-md-8">

                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="accusedSaveBtn">Save Accused</button>
                                    <input type="hidden" id="accused_id" name="accused_id" value="">
                                </div>
                            </div>

                        </form>
                    </div>

                    <!--END ACCUSED MODAL -->




                    <!-- FORM LOADING -->
                    <div class="form-loading">
                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </div>
                    <!-- FORM LOADING -->
                    </div>
                    <div class="modal-footer">
                        <div style="float: left;">
                            <button id="genIdBtn" type="button" class="btn btn-default">Gen new ID</button>
                            <div class="protectCont">
                                <label class="protectLabel" for="protect" title="Protect" data-toggle="popover" data-placement="top" data-trigger="hover" style="color: #8799ad; font-size:18pt;"><i class="fa fa-unlock"></i></label>
                                <input style="display:none;" id="protect" name="protect" type="checkbox">
                            </div>


                        </div>

                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button id="printBtn" type="button" class="btn btn-default"><i style="font-size: 8pt;" class="glyphicon glyphicon-list-alt"></i>  view</button>
                        <button id="saveBtn" data-toggle="popover" data-placement="top" data-trigger="focus" type="button" class="btn btn-success"><i class="glyphicon glyphicon-save"></i>  Save</button>
                        <button id="updateBtn" data-toggle="popover" data-placement="top" data-trigger="focus" type="button" class="btn btn-success"><i class="glyphicon glyphicon-save"></i>  Save changes</button>

                    </div>
                </div>
            </div>

            </form>

        </div>
        <!-- END MODAL -->



        <!-- MAIN CONTENT WRAPPER -->
        <div class="main-wrapper">
            <!-- data table control pannel -->
            <div class="row" id="actionPanel">
                <div class="col-md-3">
                    <div class="panel-section">
                        <div>
                            <p>Report period</p>
                            <label for="from" style="font-weight: 300;">From</label>
                            <input type="date" id="from" name="from">
                            <label for="to" style="font-weight: 300;">to</label>
                            <input type="date" id="to" name="to">

                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel-section">
                        <div>
                            <p>Print Reports</p>
                            <select name="printReportSelect" id="printReportSelect">
                                <option value="Print All" selected>Print All</option>
                                <option value="Court Referrals">Court Referrals</option>
                                <option value="WASH Court Referrals">WASH Court Referrals</option>
                                <option value="Client Ages">Client Ages</option>
                                <option value="Client Information">Client Information</option>
                                <option value="Occurrence Details">Occurrence Details</option>
                                <option value="Referrals Provided">Referrals Provided</option>
                                <option value="Court Calls">Court Calls</option>
                                <option value="Occurrence Types">Occurrence Types</option>
                            </select>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel-section">
                        <div>
                            <p>Total Number Of Clients</p>
                            <div id="totalRecs"></div>
                        </div>

                    </div>

                </div>
                <div class="col-md-3">
                    <div class="panel-section">
                        <div>
                            <p>Search</p>
                            <div style="position:relative;">
                                <i style="position: absolute; left: 85%; top: 32%; color:#333" class="glyphicon glyphicon-search"></i><input id="search" type="text" name="search" placeholder="ID, Name, Surname..">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- data table control pannel -->

            <!-- DATA TABLE -->
            <div style="margin:0px !important;padding:0px !important" class="reportsTableContain row">
                <div id="client-data">
                    <!-- MAIN LOADING -->
                    <div class="main-loading">
                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </div>
                    <!-- MAIN LOADING -->

                    <div id="refreshCont" class="row">
                        <div class="col-md-12"><button class="refreshBtn" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh Table</button></div>
                    </div>

                    <div id="headers" class="row">
                        <div class="col-md-1">Client ID</div>
                        <div class="col-md-2">Client Name <i id="client_surname" class="glyphicon glyphicon-triangle-bottom"></i></div>
                        <div class="col-md-1">Gender <i id="client_gender" class="glyphicon glyphicon-triangle-bottom"></i></div>
                        <div class="col-md-2">Age Range <i id="client_age_range" class="glyphicon glyphicon-triangle-bottom"></i></div>
                        <div class="col-md-2">Intake Date <i id="client_intake_date" class="glyphicon glyphicon-triangle-bottom"></i></div>
                        <div class="col-md-1">Incident#</div>
                        <div class="col-md-1">Actions</div>
                    </div>
                    <div id="dataRows"></div>


                </div>
                <div style="margin:0px !important;padding:0px !important">
                    <div style="margin:auto !important;padding:0px !important" id="reports" class="row"></div>
                </div>



            </div>
            <!-- DATA TABLE -->

        </div>
        <!-- END MAIN CONTENT WRAPPER -->







        <footer style="text-align: center; margin:50px;">
            <p style="color: #C0C5CB">In development by <a style="color:#C0C5CB;" href="mailto:haranzalez@gmail.com">Hans Aranzalez</a></p>
        </footer>

        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script src="js/chosen.jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/parsley.min.js"></script>
        <script src="js/app.js"></script>


</body>

</html>