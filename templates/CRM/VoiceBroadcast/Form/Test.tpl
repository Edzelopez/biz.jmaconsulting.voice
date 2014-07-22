{*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.4                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*}
<div class="crm-block crm-form-block crm-mailing-test-form-block">
{include file="CRM/common/WizardHeader.tpl"}
<div id="help">
    {ts}It's a good idea to test your voice broadcast by sending it to yourself and/or a selected group of people in your organization{/ts} {help id="test-intro"}
</div>

{include file="CRM/Mailing/Form/Count.tpl"}

<fieldset>
  <legend>Test Voice Broadcast</legend>
  <table class="form-layout">
    <tr class="crm-mailing-test-form-block-test_email"><td class="label">{$form.test_voice.label}</td><td>{$form.test_voice.html} </td></tr>
    <tr class="crm-mailing-test-form-block-test_group"><td class="label">{$form.test_group.label}</td><td>{$form.test_group.html}</td></tr>
    <tr><td></td><td>{$form.sendtest.html}</td></tr>
    <tr><td></td><td>{$form.listenvoice.html}</td></tr>
  </table>
</fieldset>

<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl"}</div>
    
</div><!-- / .crm-form-block -->

{* include jscript to warn if unsaved form field changes *}
{include file="CRM/common/formNavigate.tpl"}
{literal}
<script type="text/javascript">
cj(function() {
   cj().crmAccordions(); 
});
</script>
{/literal}

