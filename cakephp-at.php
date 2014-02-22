<?php
App::import('Vendor','nusoap');


class WebservicesController extends AppController {


public function beforeFilter() {
    	
		parent::beforeFilter();
     	$this->Auth->allow('test');
		$this->Auth->allow('test1');
		$this->Auth->allow('get_contacts_for_account');
	
    }

public function index() 
{
        //$this->Issues->recursive = 0;
       // $this->set('Projects', $this->paginate());
}


public function connection()
{
	$location = 'https://webservices5.Autotask.net/atservices/1.5/atws.asmx';
    $uri      = 'https://webservices.autotask.net';
    $wsdl     = 'https://webservices5.Autotask.net/atservices/1.5/atws.wsdl';

    $options = array(
     'login' => 'YOUR_LOGIN',
     'password' => 'YOUR_PASSWORD',
     'location' => $location,
     'uri' => $uri,
    );
	
    $loginarray = array('login' => "YOUR_LOGIN", 'password' => "YOUR_PASSWORD", 'uri'=>"https://webservices.autotask.net", 'location'=>"https://webservices5.Autotask.net/atservices/1.5/atws.asmx");
    $client = new SoapClient($wsdl, $loginarray);
	
	return $client;
}

public function get_account_name_from_project()
{
	
	$atask = $this->connection();
	
	$proj_name1 = 'Country Sites';
	
	$condition1 = 'equals';
	$condition2 = 'NotEqual';
	
	$p ="";
	$project_status1 =1;
	$project_status2 =13;
	
	$id = 1;

	$xml1 = array('sXML' => "
    <queryxml>
        <entity>Project</entity>
        <query>
            <condition>
				<field>ProjectName
					<expression op=\"$condition2\">".$p."</expression>
				</field>
			</condition>
			<condition>
				<field>Status
					<expression op=\"$condition1\">".$project_status1."</expression>
				</field>
			</condition>
			<condition operator=\"OR\">
				<field>Status
					<expression op=\"$condition1\">".$project_status2."</expression>
				</field>
			</condition>
			
        </query>
    </queryxml>");
			
	$projects = $atask->query($xml1);
		
	$projects = $projects->queryResult->EntityResults->Entity;
	
	$projects = (array)$projects;

		
		
	foreach($projects as $i => $proj)
	{
			
		
		
		$account_id = $proj->AccountID;
	
		$xml2 = array('sXML' => "
            <queryxml>
                <entity>Account</entity>
                <query>
                    <field>id<expression op=\"$condition1\">".$account_id."</expression></field>
                </query>
            </queryxml>");
	
		$account_detail = $atask->query($xml2);
		
		$projects = (array)$projects;
		
		$projects[$i] = (array)$projects[$i];
		
		$projects[$i]['AccountName']= $account_detail->queryResult->EntityResults->Entity->AccountName;
		
		
		
	}

	
	return $projects;
		
	
}

public function get_contacts_for_account($account_id=1229764)
{
	
	$atask = $this->connection();
	$condition1 = 'equals';
	$value = $account_id;
	$value1 = '0';
	$operator = 'OR';
	

		$xml2 = array('sXML' => "
	            <queryxml>
	                <entity>Contact</entity>
	                <query>
	                <condition >
	                    <field>AccountID<expression op=\"$condition1\">".$value."</expression></field>
	                </condition> 
	               
	                </query>
	            </queryxml>");
				
			$accounts = $atask->query($xml2);	
			return $accounts->queryResult->EntityResults->Entity;
			
	$accounts = $atask->query($xml2);
	
	return $accounts->queryResult->EntityResults->Entity;
	
}

public function get_projects_for_account($account_id)
{
	//echo $account_id;	
	
	$atask = $this->connection();
	$condition1 = 'equals';
	$value = $account_id;
	
	$xml2 = array('sXML' => "
            <queryxml>
                <entity>Project</entity>
                <query>
                <condition >
                    <field>AccountID<expression op=\"$condition1\">".$value."</expression></field>
                </condition> 
                </query>
            </queryxml>");
			
	
	$projects = $atask->query($xml2);
	
	if(!empty($projects->queryResult->EntityResults->Entity))
	{	
		return $projects->queryResult->EntityResults->Entity;
	}
	else
	{
		return 0;		
	}
	
}

public function get_all_accounts()
{
	$atask = $this->connection();
	
	$condition1 = 'IsNotNull';
	$value = "";
		
	$xml2 = array('sXML' => "
            <queryxml>
                <entity>Account</entity>
                <query>
                    <field>AccountName<expression op=\"$condition1\">".$value."</expression></field>
                </query>
            </queryxml>");
	
	$accounts = $atask->query($xml2);
	
	return $accounts->queryResult->EntityResults->Entity;
	
}

public function get_resources()
{
	
	$atask = $this->connection();
	
	$condition1 = 'IsNotNull';
	$value = "";
		
	$xml2 = array('sXML' => "
            <queryxml>
                <entity>Resource</entity>
                <query>
                    <field>id<expression op=\"$condition1\">".$value."</expression></field>
                </query>
            </queryxml>");
	
	$resources = $atask->query($xml2);
	
	return $resources->queryResult->EntityResults->Entity;
}


}