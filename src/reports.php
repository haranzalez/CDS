<?php 

include 'dbcred.php';
$d = $db;
$reportQuery = (isset($_GET['rq']))?$_GET['rq']:false;
$reportPeriodFrom = (isset($_GET['rpf']))?$_GET['rpf']:false;
$reportPeriodTo = (isset($_GET['rpt']))?$_GET['rpt']:false;

if($reportQuery !== false)
{
    $y = date('y');
    $m = date('n');
    $date = $m.'-'.$y;
    $tables = array(//Court Referals
        'Court Referrals.'.$date => array(
            'Brantford Police' => 'clients WHERE (client_referred_by = "Brantford Police") AND',
            'OPP' => 'clients WHERE (client_referred_by = "OPP") AND',
            'Six Nations Police' => 'clients WHERE (client_referred_by = "Six Nations Police") AND'
        ),
        'Client Ages.'.$date => array(//Client Ages
            
            "Under 16" => "clients WHERE (client_age_range = 'Under 16') AND",
            "16-19" => "clients WHERE (client_age_range = '16-19') AND",
            "20-25" => "clients WHERE (client_age_range = '20-25') AND",
            "26-45" => "clients WHERE (client_age_range = '26-45') AND",
            "46-65" => "clients WHERE (client_age_range = '46-65') AND",
            "65+" => "clients WHERE (client_age_range = '65+') AND"
        ),
        'Client Information.'.$date => array(//Client Information
            "Females" => "clients WHERE (client_gender = 'Female') AND",
            "Males" => "clients WHERE (client_gender = 'Male') AND",
            "Aboriginals" => "clients WHERE (client_indigenous = 1) AND",
            "Crown Contact Forms" => "clients WHERE (client_referred_by LIKE '%CROWN') AND",
            "Client Unable to Contact" => "clients WHERE",
            "Information Letter Sent" => "clients WHERE"
        ),
        'Occurrence Details.'.$date => array(//Occurrence Details
            "Male Accused" => "accused WHERE (accused_gender = 'Male') AND",
            "Female Accused" => "accused WHERE (accused_gender = 'Female') AND",
            "Domestic Violence Incidents" => "clients WHERE (client_domestic_violence = 1) AND",
            "Young Offender Occurrences" => "accused WHERE (accused_young_offender = 1) AND",
            "Historical Occurrences" => "accused WHERE"
        ),
       'Referrals Provided.'.$date => array(//Referrals Provided
            "ALERT" => "clients WHERE",
            "Victim Impact Statements" => "clients WHERE",
            "VWAP Mandate" => "clients WHERE",
            "VQRP" => "clients WHERE",
            "CAS Notifications" => "children WHERE",
            "Agency Linkage" => "clients WHERE",
            "Agency Linkage Calls" => "clients WHERE"
        
        ),
         'Court Calls.'.$date => array(//Court Calls
            "Information Calls" => "clients WHERE",
            "Initial Court Calls - attempted" => "clients WHERE",
            "Initial Court Calls - completed" => "clients WHERE",
            "Remand Calls - attempted" => "clients WHERE",
            "Remand Calls - completed" => "clients WHERE",
            "Release/Detain Calls - attempted" => "clients WHERE",
            "Release/Detain Calls - completed" => "clients WHERE"
        ),
        'Occurrence Types.'.$date => array(//Occurrence Types
            "Accident cause fatality" => "clients WHERE (client_charges LIKE '%Accident%% %%cause%% %%fatality%') AND",
            "Aggravated assault" => "clients WHERE (client_charges LIKE '%Aggravated%% %%assault%') AND",
            "Assault cause bandily harm" => "clients WHERE (client_charges LIKE '%Assault%% %%cause%% %%bandily%% %%harm%') AND",
            "Assault with a weapon" => "clients WHERE (client_charges LIKE '%Assault%% %%with%% %%a%% %%weapon%') AND",
            "Advertising sex service under 18" => "clients WHERE (client_charges LIKE '%Advertising%% %%sex%% %%service%% %%under%% %%18%') AND",
            "Assault" => "clients WHERE (client_charges LIKE '%Assault%') AND",
            "Arson" => "clients WHERE (client_charges LIKE '%Arson%') AND",
            "Breach court order" => "clients WHERE (client_charges LIKE '%Breach%% %%court%% %%order%') AND",
            "Criminal Harasment" => "clients WHERE (client_charges LIKE '%Criminal%% %%Harasment%') AND",
            "Disguise with intent" => "clients WHERE (client_charges LIKE '%Disguise%% %%with%% %%intent%') AND",
            "Exceed 80 milligrams" => "clients WHERE (client_charges LIKE '%Exceed%% %%80%% %%milligrams%') AND",
            "Forcible confinement" => "clients WHERE (client_charges LIKE '%Forcible%% %%confinement%') AND",
            "Fraud/Theft" => "clients WHERE (client_charges LIKE '%Fraud/Theft%') AND",
            "Firearm in motor vehicle" => "clients WHERE (client_charges LIKE '%Firearm%% %%in%% %%motor%% %%vehicle%') AND",
            "Human trafficking" => "clients WHERE (client_charges LIKE '%Human%% %%trafficking%') AND",
            "Impaired causing death" => "clients WHERE (client_charges LIKE '%Impaired%% %%causing%% %%death%') AND",
            "Intimidation" => "clients WHERE (client_charges LIKE '%Intimidation%') AND",
            "Mischief" => "clients WHERE (client_charges LIKE '%Mischief%') AND",
            "Murder/Attempt Murder/Manslaughter" => "clients WHERE (client_charges LIKE '%Murder/Attempt%% %%Murder/Manslaughter%') AND",
            "Mischief/Cause disturbance" => "clients WHERE (client_charges LIKE '%Mischief/Cause%% %%disturbance%') AND",
            "Making child pornography" => "clients WHERE (client_charges LIKE '%Making%% %%child%% %%pornography%') AND",
            "Other" => "clients WHERE (client_charges LIKE '%Other%') AND",
            "Possession child pornography" => "clients WHERE (client_charges LIKE '%Possession%% %%child%% %%pornography%') AND",
            "Procuring trafficking persons" => "clients WHERE (client_charges LIKE '%Procuring%% %%trafficking%% %%persons%') AND",
            "Point firearm" => "clients WHERE (client_charges LIKE '%Point%% %%firearm%') AND",
            "Possession of firearm" => "clients WHERE (client_charges LIKE '%Possession%% %%of%% %%firearm%') AND",
            "Procuring under 18" => "clients WHERE (client_charges LIKE '%Procuring%% %%under%% %%18%') AND",
            "Possession of a weapon" => "clients WHERE (client_charges LIKE '%Possession%% %%of%% %%a%% %%weapon%') AND",
            "Robbery/Break and enter" => "clients WHERE (client_charges LIKE '%Robbery/Break%% %%and%% %%enter%') AND",
            "Robbery with firearm" => "clients WHERE (client_charges LIKE '%Robbery%% %%with%% %%firearm%') AND",
            "Robbery with weapon" => "clients WHERE (client_charges LIKE '%Robbery%% %%with%% %%weapon%') AND",
            "Strangulation" => "clients WHERE (client_charges LIKE '%Strangulation%') AND",
            "Sexual assault" => "clients WHERE (client_charges LIKE '%Sexual%% %%assault%') AND",
            "Unauthorized possession of a firearm" => "clients WHERE (client_charges LIKE '%Unauthorized%% %%possession%% %%of%% %%a%% %%firearm%') AND",
            "Unlawfully in a dwelling" => "clients WHERE (client_charges LIKE '%Unlawfully%% %%in%% %%a%% %%dwelling%') AND",
            "Uttering Threats" => "clients WHERE (client_charges LIKE '%Uttering%% %%Threats%') AND",
            "Voyeurism" => "clients WHERE (client_charges LIKE '%Voyeuris%') AND"
            
        )
    );
        
    
    if($reportQuery == 'all')
    {
        $allTables = array();
        
        foreach($tables as $key => $value)
        {
            if($key == 'date')
            {
                $allTables[$key] = $value;
            }else
            {
                $resTable = array();
                foreach($value as $k => $v)
                {
                    $que = "SELECT * FROM ". $v . " (timestamp BETWEEN '1970-01-01' AND NOW())";

                    $val = sendRequest($que, $d);
                    $resTable[$k] = $val;        
                }
                $allTables[$key] = $resTable;
            }
           
        }
        
        echo json_encode($allTables);
        
        
    }
    if($reportQuery == 'period' && $reportPeriodFrom !== false && $reportPeriodTo !== false)
    {
        
        
       $allTables = array();
        
        foreach($tables as $key => $value)
        {
            
            if($key == 'date')
            {
                $allTables[$key] = $value;
            }else
            {
                $resTable = array();
                foreach($value as $k => $v)
                {
                    $que = "SELECT * FROM ". $v . " (timestamp BETWEEN '".date($reportPeriodFrom)."' AND '".date($reportPeriodTo)."')";

                    $val = sendRequest($que, $d);
                    $resTable[$k] = $val;        
                }
                $allTables[$key] = $resTable;
            }
           
        }
        
        echo json_encode($allTables);
    }
}

function sendRequest($sql, $d)
{
    try{
            $q = $d->query($sql);
            $results = array();
            while($row = $q->fetch(PDO::FETCH_ASSOC)) {
                $results[] = $row;
            }
            return count($results);
        }
    catch(PDOException $ex)
        {
            print_r($d->errorInfo());
        }

}




?>
