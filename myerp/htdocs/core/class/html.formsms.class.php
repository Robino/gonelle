<?PHP
/* Copyright (C) 2005-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2010      Juanjo Menent        <jmenent@2byte.es>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *       \file       htdocs/core/class/html.formmail.class.php
 *       \ingroup    core
 *       \brief      Fichier de la classe permettant la generation du formulaire html d'envoi de mail unitaire
 */
require_once(DOL_DOCUMENT_ROOT ."/core/class/html.form.class.php");


/**     \class      FormSms
 *      \brief      Classe permettant la generation du formulaire d'envoi de Sms
 *      \remarks    Utilisation: $formsms = new FormSms($db)
 *      \remarks                 $formsms->proprietes=1 ou chaine ou tableau de valeurs
 *      \remarks                 $formsms->show_form() affiche le formulaire
 */
class FormSms
{
	var $db;

	var $fromname;
	var $fromsms;
	var $replytoname;
	var $replytomail;
	var $toname;
	var $tomail;

	var $withsubstit;			// Show substitution array
	var $withfrom;
	var $withto;
	var $withtopic;
	var $withbody;

	var $withfromreadonly;
	var $withreplytoreadonly;
	var $withtoreadonly;
	var $withtopicreadonly;
	var $withcancel;

	var $substit=array();
	var $param=array();

	var $error;


	/**
	 *	Constructor
	 *
	 *  @param		DoliDB		$DB      Database handler
	 */
	function FormSms($DB)
	{
		$this->db = $DB;

		$this->withfrom=1;
		$this->withto=1;
		$this->withtopic=1;
		$this->withbody=1;

		$this->withfromreadonly=1;
		$this->withreplytoreadonly=1;
		$this->withtoreadonly=0;
		$this->withtopicreadonly=0;
		$this->withbodyreadonly=0;

		return 1;
	}

	/**
	 *	Show the form to input an sms
	 */
	function show_form($width='180px')
	{
		global $conf, $langs, $user;

		$langs->load("other");
		$langs->load("mails");
		$langs->load("sms");

		$form=new Form($this->db);
        $soc=new Societe($this->db);
		if (!empty($this->withtosocid) && $this->withtosocid > 0)
        {
            $soc->fetch($this->withtosocid);
        }

		print "\n<!-- Debut form SMS -->\n";

    print '
<script language="javascript">
function limitChars(textarea, limit, infodiv)
{
    var text = textarea.value;
    var textlength = text.length;
    var info = document.getElementById(infodiv);

    info.innerHTML = \''.$langs->trans("SmsInfoCharRemain").': \' + (limit - textlength);
    return true;
}
</script>';

		print "<form method=\"POST\" name=\"smsform\" enctype=\"multipart/form-data\" action=\"".$this->param["returnurl"]."\">\n";
		print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
		foreach ($this->param as $key=>$value)
		{
			print "<input type=\"hidden\" name=\"$key\" value=\"$value\">\n";
		}
		print "<table class=\"border\" width=\"100%\">\n";

		// Substitution array
		if ($this->withsubstit)
		{
			print "<tr><td colspan=\"2\">";
			$help="";
			foreach($this->substit as $key => $val)
			{
				$help.=$key.' -> '.$langs->trans($val).'<br>';
			}
			print $form->textwithpicto($langs->trans("SmsTestSubstitutionReplacedByGenericValues"),$help);
			print "</td></tr>\n";
		}

		// From
		if ($this->withfrom)
		{
			if ($this->withfromreadonly)
			{
				print '<input type="hidden" name="fromsms" value="'.$this->fromsms.'">';
				print "<tr><td width=\"".$width."\">".$langs->trans("SmsFrom")."</td><td>";
				if ($this->fromtype == 'user')
				{
					$langs->load("users");
					$fuser=new User($this->db);
					$fuser->fetch($this->fromid);
					print $fuser->getNomUrl(1);
					print ' &nbsp; ';
				}
				if ($this->fromsms)
				{
					print $this->fromsms;
				}
				else
				{
					if ($this->fromtype)
					{
						$langs->load("errors");
						print '<font class="warning"> &lt;'.$langs->trans("ErrorNoPhoneDefinedForThisUser").'&gt; </font>';
					}
				}
				print "</td></tr>\n";
				print "</td></tr>\n";
			}
			else
			{
				print "<tr><td width=\"".$width."\">".$langs->trans("SmsFrom")."</td><td>";
                //print '<input type="text" name="fromname" size="30" value="'.$this->fromsms.'">';
				if ($conf->global->MAIN_SMS_SENDMODE == 'ovh')
                {
                    dol_include_once('/ovh/class/ovhsms.class.php');
                    try
                    {
                        $sms = new OvhSms($this->db);
                        if (empty($conf->global->OVHSMS_ACCOUNT))
                        {
                            $resultsender = 'ErrorOVHSMS_ACCOUNT not defined';
                        }
                        else
                        {
                            $resultsender = $sms->SmsSenderList($conf->global->OVHSMS_ACCOUNT);
                        }
                    }
                    catch(Exception $e)
                    {
                        dol_print_error('','Error to get list of senders: '.$e->getMessage());
                    }
                }
                else
                {
                    dol_syslog("Warning: The SMS sending method has not been defined into MAIN_SMS_SENDMODE", LOG_WARNING);
                    $resultsender[0]->number=$this->fromsms;
                }
                if (is_array($resultsender) && count($resultsender) > 0)
                {
                    print '<select name="fromsms" id="valid" class="flat">';
                    foreach($resultsender as $obj)
                    {
                        print '<option value="'.$obj->number.'">'.$obj->number.'</option>';
                    }
                    print '</select>';
                }
                else print '<span class="error">'.$langs->trans("SmsNoPossibleRecipientFound").'</span>';
                print '</td>';
				print "</tr>\n";
			}
		}

		// To
		if ($this->withto || is_array($this->withto))
		{
			print '<tr><td width="180">';
			//$moretext=$langs->trans("YouCanUseCommaSeparatorForSeveralRecipients");
			$moretext='';
			print $form->textwithpicto($langs->trans("SmsTo"),$moretext);
			print '</td><td>';
			if ($this->withtoreadonly)
			{
				print (! is_array($this->withto) && ! is_numeric($this->withto))?$this->withto:"";
			}
			else
			{
			    print "<input size=\"16\" id=\"sendto\" name=\"sendto\" value=\"".(! is_array($this->withto) && $this->withto != '1'? (isset($_REQUEST["sendto"])?$_REQUEST["sendto"]:$this->withto):"+")."\">";
				if (! empty($this->withtosocid) && $this->withtosocid > 0)
				{
					$liste=array();
					foreach ($soc->thirdparty_and_contact_phone_array() as $key=>$value)
					{
						$liste[$key]=$value;
					}
					print " ".$langs->trans("or")." ";
					//var_dump($_REQUEST);exit;
					print $form->selectarray("receiver", $liste, GETPOST("receiver"), 1);
				}
				print ' '.$langs->trans("SmsInfoNumero");
			}
			print "</td></tr>\n";
		}

		// Message
		if ($this->withbody)
		{
			$defaultmessage='';
			if ($this->param["models"]=='body') 			{ $defaultmessage=$this->withbody; }
			$defaultmessage=make_substitutions($defaultmessage,$this->substit,$langs);
			if (isset($_POST["message"])) $defaultmessage=$_POST["message"];
			$defaultmessage=str_replace('\n',"\n",$defaultmessage);

			print "<tr>";
			print "<td width=\"180\" valign=\"top\">".$langs->trans("SmsText")."</td>";
			print "<td>";
			if ($this->withbodyreadonly)
			{
				print nl2br($defaultmessage);
				print '<input type="hidden" name="message" value="'.$defaultmessage.'">';
			}
			else
			{
                print '<textarea cols="40" name="message" id="message" rows="4" onkeyup="limitChars(this, 160, \'charlimitinfo\')">'.$defaultmessage.'</textarea>';
                print '<div id="charlimitinfo">'.$langs->trans("SmsInfoCharRemain").': <span id="charlimitinfospan">'.(160-dol_strlen($defaultmessage)).'</span></div></td>';
			}
			print "</td></tr>\n";
		}

		print '
           <tr>
            <td>'.$langs->trans("DelayBeforeSending").':</td>
            <td> <input name="deferred" id="deferred" size="4" value="0"></td></tr>

           <tr><td>'.$langs->trans("Priority").' :</td><td>
           <select name="priority" id="valid" class="flat">
           <option value="0">0</option>
           <option value="1">1</option>
           <option value="2">2</option>
           <option value="3" selected="selected">3</option>
           </select></td></tr>

           <tr><td>'.$langs->trans("Type").' :</td><td>
           <select name="class" id="valid" class="flat">
           <option value="0">Flash</option>
           <option value="1" selected="selected">Standard</option>
           <option value="2">SIM</option>
           <option value="3">ToolKit</option>
           </select></td></tr>';

		print '<tr><td align="center" colspan="2"><center>';
		print "<input class=\"button\" type=\"submit\" name=\"sendmail\" value=\"".$langs->trans("SendSms")."\"";
		print ">";
		if ($this->withcancel)
		{
			print " &nbsp; &nbsp; ";
			print "<input class=\"button\" type=\"submit\" name=\"cancel\" value=\"".$langs->trans("Cancel")."\">";
		}
		print "</center></td></tr>\n";
		print "</table>\n";

		print "</form>\n";
		print "<!-- Fin form SMS -->\n";
	}

}

?>