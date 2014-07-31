<?php


/**
 * Choose include / exclude groups and mailings
 *
 */
class CRM_VoiceBroadcast_Form_Group extends CRM_Contact_Form_Task
{

  /**
   * the mailing ID of the mailing if we are resuming a mailing
   *
   * @var integer
   */
  protected $_mailingID;

  /**
   * Function to set variables up before form is built
   *
   * @return void
   * @access public
   */
  public function preProcess()
  {
    $this->_mailingID = CRM_Utils_Request::retrieve('mid', 'Integer', $this, FALSE, NULL);
  }

  /**
   * This function sets the default values for the form.
   * the default values are retrieved from the database
   *
   * @access public
   *
   * @return None
   */
  function setDefaultValues()
  {
    $continue = CRM_Utils_Request::retrieve('continue', 'String', $this, FALSE, NULL);

    $defaults = array();
    if ($this->_mailingID) {

      $em = $entityManager  = require __DIR__. '/../../../bootstrap.php';
      $voiceBroadcastEntity = $em->find('CRM\Voice\Entities\CivicrmVoiceBroadcast', $this->_mailingID);

      $defaults['name'] = $voiceBroadcastEntity->getName();
      if (!$continue) {
        $defaults['name'] = ts('Copy of %1', array(1 => $voiceBroadcastEntity->getName()));
      }
      else {
        // CRM-7590, reuse same mailing ID if we are continuing
        $this->set('mailing_id', $this->_mailingID);
      }

      $defaults['campaign_id'] = $voiceBroadcastEntity->getCampaignId();
      $defaults['dedupe_email'] = null;

      $voiceBroadcastGroupEntities = $em->find('CRM\Voice\Entities\CivicrmVoiceBraodcastGroup', $this->_mailingID);

      $mailingGroups = array('civicrm_group'     => array( ),
                             'civicrm_mailing'   => array( )
      );

      $entityTable = 'civicrm_group';
      foreach($voiceBroadcastGroupEntities as $voiceBroadcastGroupEntity)
      {
        $mailingGroups[$entityTable][$voiceBroadcastGroupEntity->getGroupType()][] = $voiceBroadcastGroupEntity->getEntityId();
      }

      $defaults['includeGroups'] = $mailingGroups['civicrm_group']['Include'];
      $defaults['excludeGroups'] = CRM_Utils_Array::value('Exclude', $mailingGroups['civicrm_group']);
    }

    //when the context is search hide the mailing recipients.
    $showHide = new CRM_Core_ShowHideBlocks();
    $showGroupSelector = TRUE;


    if ($showGroupSelector) {
      $showHide->addShow("id-additional");
      $showHide->addHide("id-additional-show");
    }
    else {
      $showHide->addShow("id-additional-show");
      $showHide->addHide("id-additional");
    }
    $showHide->addToTemplate();

    return $defaults;
  }

  /**
   * Function to actually build the form
   *
   * @return None
   * @access public
   */
  public function buildQuickForm()
  {

    //get the context
//    $context = $this->get('context');
//    if ($this->_searchBasedMailing) {
//      $context = 'search';
//    }
//    $this->assign('context', $context);

    $this->add('text',
               'name',
               ts('Name Your Voice Broadcast'),
               'name',
                TRUE
    );

    $hiddenMailingGroup = NULL;
    $campaignId         = NULL;
    $defaults           = array();

    //CRM-7362 --add campaigns.
    if ($this->_mailingID) {
      $campaignId = CRM_Core_DAO::getFieldValue('CRM_Mailing_DAO_Mailing', $this->_mailingID, 'campaign_id');
      $hiddenMailingGroup = CRM_Mailing_BAO_Mailing::hiddenMailingGroup($this->_mailingID);
    }
    CRM_Campaign_BAO_Campaign::addCampaign($this, $campaignId);

    //dedupe on email option
    $this->addElement('checkbox', 'is_public', ts('Phone'), ts(' Is Public'));
    $defaults['is_public'] = true;

    $phone_location = array(1 => 'Home', 2 => 'Main', 3 => 'Other', 4 => 'Work');
    $this->addElement('select','phone_location','Phone Location: ',$phone_location);


    $phone_type = array(1 => 'Phone', 2 => 'Mobile', 3 => 'Fax', 4 => 'Pager', 5 => 'Voice Mail');
    $this->addElement('select','phone_type','Phone Type: ',$phone_type);

    //get the mailing groups.
    $groups = CRM_Core_PseudoConstant::group('Mailing');
    if ($hiddenMailingGroup) {
      $groups[$hiddenMailingGroup] =
        CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_Group', $hiddenMailingGroup, 'title');
    }

    $mailings = CRM_Mailing_PseudoConstant::completed();
    if (!$mailings) {
      $mailings = array();
    }

    // run the groups through a hook so users can trim it if needed
    CRM_Utils_Hook::mailingGroups($this, $groups, $mailings);



    if (count($groups) <= 10) {
      // setting minimum height to 2 since widget looks strange when size (height) is 1
      $groupSize = max(count($groups), 2);
    }
    else {
      $groupSize = 10;
    }
    $inG = &$this->addElement('advmultiselect', 'includeGroups',
      ts('Include Group(s)') . ' ',
      $groups,
      array(
        'size' => $groupSize,
        'style' => 'width:auto; min-width:240px;',
        'class' => 'advmultiselect',
      )
    );



    $outG = &$this->addElement('advmultiselect', 'excludeGroups',
      ts('Exclude Group(s)') . ' ',
      $groups,
      array(
        'size' => $groupSize,
        'style' => 'width:auto; min-width:240px;',
        'class' => 'advmultiselect',
      )
    );

    $inG->setButtonAttributes('add', array('value' => ts('Add >>')));
    $outG->setButtonAttributes('add', array('value' => ts('Add >>')));
    $inG->setButtonAttributes('remove', array('value' => ts('<< Remove')));
    $outG->setButtonAttributes('remove', array('value' => ts('<< Remove')));

    if (count($mailings) <= 10) {
      // setting minimum height to 2 since widget looks strange when size (height) is 1
      $mailingSize = max(count($mailings), 2);
    }
    else {
      $mailingSize = 10;
    }
    $inM = &$this->addElement('advmultiselect', 'includeMailings',
      ts('INCLUDE Recipients of These Mailing(s)') . ' ',
      $mailings,
      array(
        'size' => $mailingSize,
        'style' => 'width:auto; min-width:240px;',
        'class' => 'advmultiselect',
      )
    );
    $outM = &$this->addElement('advmultiselect', 'excludeMailings',
      ts('EXCLUDE Recipients of These Mailing(s)') . ' ',
      $mailings,
      array(
        'size' => $mailingSize,
        'style' => 'width:auto; min-width:240px;',
        'class' => 'advmultiselect',
      )
    );

    $inM->setButtonAttributes('add', array('value' => ts('Add >>')));
    $outM->setButtonAttributes('add', array('value' => ts('Add >>')));
    $inM->setButtonAttributes('remove', array('value' => ts('<< Remove')));
    $outM->setButtonAttributes('remove', array('value' => ts('<< Remove')));

    $urls = array('' => ts('- select -'), -1 => ts('CiviCRM Search'),
    ) + CRM_Contact_Page_CustomSearch::info();

    $this->addFormRule(array('CRM_Mailing_Form_Group', 'formRule'));

    $buttons = array(
      array('type' => 'next',
        'name' => ts('Next >>'),
        'spacing' => '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;',
        'isDefault' => TRUE,
      ),
      array(
        'type' => 'submit',
        'name' => ts('Save & Continue Later'),
      ),
      array(
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ),
    );

    $this->addButtons($buttons);

    $this->setDefaults($defaults);

    $this->assign('groupCount', count($groups));
    $this->assign('mailingCount', count($mailings));
    if(count($groups) == 0 && count($mailings) == 0 && !$this->_searchBasedMailing) {
      CRM_Core_Error::statusBounce("To send a mailing, you must have a valid group of recipients - either at least one group that's a Mailing List or at least one previous mailing or start from a search");
    }
  }

  public function postProcess()
  {
    $entityManager = require __DIR__. '/../../../bootstrap.php';
    $values        = $this->controller->exportValues($this->_name);

    //build hidden smart group. when user want to send  mailing
    //through search contact-> more action -> send Mailing. CRM-3711
    $groups         = array();

    foreach (array('name', 'group_id', 'search_id', 'search_args', 'campaign_id', 'is_public', 'phone_location', 'phone_type') as $n)
    {
      if (CRM_Utils_Array::value($n, $values)) {
        $params[$n] = $values[$n];
      }
    }

    $this->set('name', $params['name']);

    $inGroups    = $values['includeGroups'];
    $outGroups   = $values['excludeGroups'];
    $inMailings  = $values['includeMailings'];
    $outMailings = $values['excludeMailings'];

    if (is_array($inGroups)) {
      foreach ($inGroups as $key => $id) {
        if ($id) {
          $groups['include'][] = $id;
        }
      }
    }
    if (is_array($outGroups)) {
      foreach ($outGroups as $key => $id) {
        if ($id) {
          $groups['exclude'][] = $id;
        }
      }
    }

    $mailings = array();
    if (is_array($inMailings)) {
      foreach ($inMailings as $key => $id) {
        if ($id) {
          $mailings['include'][] = $id;
        }
      }
    }
    if (is_array($outMailings)) {
      foreach ($outMailings as $key => $id) {
        if ($id) {
          $mailings['exclude'][] = $id;
        }
      }
    }

    $session            = CRM_Core_Session::singleton();
    $params['groups']   = $groups;
    $params['mailings'] = $mailings;
    $ids = array();

    if ($this->get('mailing_id')) {

      // don't create a new mailing if already exists
      $ids['mailing_id'] = $this->get('mailing_id');

      $voiceBroadcastGroupEntities = $entityManager->find('CRM\Voice\Entities\CivicrmVoiceBraodcastGroup', $ids['mailing_id']);

      if(!empty($voiceBroadcastGroupEntities)) {

          foreach($voiceBroadcastGroupEntities as $voiceBroadcastGroupEntity)
          {
            $entityManager->remove($voiceBroadcastGroupEntity);
          }

          $entityManager->flush();

      }
    } else {
      // new mailing, so lets set the created_id
      $session = CRM_Core_Session::singleton();
      $params['created_id'] = $session->get('userID');
      $params['created_date'] = date('YmdHis');
    }



    $voiceBroadCastEntity = $this->create($entityManager, $params, $ids);
    $this->set('mailing_id', $voiceBroadCastEntity->getId());

    $dedupeEmail = FALSE;
    if (isset($params['dedupe_email'])) {
      $dedupeEmail = $params['dedupe_email'];
    }

    // mailing id should be added to the form object
    $this->_mailingID = $voiceBroadCastEntity->getId();

    // also compute the recipients and store them in the mailing recipients table
    CRM_Mailing_BAO_Mailing::getRecipients(
      $this->_mailingID,
      $this->_mailingID,
      NULL,
      NULL,
      TRUE,
      $dedupeEmail
    );

    $count = CRM_Mailing_BAO_Recipients::mailingSize($this->_mailingID);
    $this->set('count', $count);
    $this->assign('count', $count);
    $this->set('groups', $groups);
    $this->set('mailings', $mailings);
  }

  /**
   * Display Name of the form
   *
   * @access public
   *
   * @return string
   */
  public function getTitle() {
    return ts('Select Recipients');
  }

  /**
   * global validation rules for the form
   *
   * @param array $fields posted values of the form
   *
   * @return array list of errors to be posted back to the form
   * @static
   * @access public
   */
  static function formRule($fields) {
    $errors = array();
    if (isset($fields['includeGroups']) &&
      is_array($fields['includeGroups']) &&
      isset($fields['excludeGroups']) &&
      is_array($fields['excludeGroups'])
    ) {
      $checkGroups = array();
      $checkGroups = array_intersect($fields['includeGroups'], $fields['excludeGroups']);
      if (!empty($checkGroups)) {
        $errors['excludeGroups'] = ts('Cannot have same groups in Include Group(s) and Exclude Group(s).');
      }
    }

    if (isset($fields['includeMailings']) &&
      is_array($fields['includeMailings']) &&
      isset($fields['excludeMailings']) &&
      is_array($fields['excludeMailings'])
    ) {
      $checkMailings = array();
      $checkMailings = array_intersect($fields['includeMailings'], $fields['excludeMailings']);
      if (!empty($checkMailings)) {
        $errors['excludeMailings'] = ts('Cannot have same mail in Include mailing(s) and Exclude mailing(s).');
      }
    }

    if (!empty($fields['search_id']) &&
      empty($fields['group_id'])
    ) {
      $errors['group_id'] = ts('You must select a group to filter on');
    }

    if (empty($fields['search_id']) &&
      !empty($fields['group_id'])
    ) {
      $errors['search_id'] = ts('You must select a search to filter');
    }

    return empty($errors) ? TRUE : $errors;
  }




  public function create(&$entityManager, &$params, $ids = array())
  {

    // CRM-12430
    // Do the below only for an insert
    // for an update, we should not set the defaults
    if (!isset($ids['id']) && !isset($ids['mailing_id'])) {
      // Retrieve domain email and name for default sender
      $domain = civicrm_api('Domain',
                            'getsingle',
                             array('version'        => 3,
                                   'current_domain' => 1,
                                   'sequential'     => 1,
                                )
                            );

      if (!isset($params['created_id'])) {
        $session =& CRM_Core_Session::singleton();
        $params['created_id'] = $session->get('userID');
      }

      $defaults = array(
        'is_track_call_cost'        => false,
        'is_track_call_duration'    =>  false,
        'is_track_call_disposition' => false,
        'voice_message_file'        => '',
        'domain_id'                 => $domain['id'],
        'contact_id'                => $domain['contact_id'],
        'created_at'                => new DateTime('now'),
        'scheduled_date'            => NULL
      );

      // Get the default from email address, if not provided.
      if (empty($defaults['from_email'])) {
        $defaultAddress = CRM_Core_OptionGroup::values('from_email_address', NULL, NULL, NULL, ' AND is_default = 1');
        foreach ($defaultAddress as $id => $value) {
          if (preg_match('/"(.*)" <(.*)>/', $value, $match)) {
            $defaults['from_email'] = $match[2];
            $defaults['from_name'] = $match[1];
          }
        }
      }

      $params = array_merge($defaults, $params);

        //Persist Voice
        $voiceEntity = new \CRM\Voice\Entities\CivicrmVoiceBroadcast();
        $voiceEntity->setName($params['name']);
        $voiceEntity->setCreatedAt(new DateTime('now'));
        $voiceEntity->setIsTrackCallCost($params['is_track_call_cost']);
        $voiceEntity->setIsTrackCallDisposition($params['is_track_call_disposition']);
        $voiceEntity->setIsTrackCallDuration($params['is_track_call_duration']);
        $voiceEntity->setDomainId($params['domain_id']);
        $voiceEntity->setContactId($params['contact_id']);
        $voiceEntity->setCampaignId(1);
        $voiceEntity->setPhoneId(1);
        $voiceEntity->setIsPrimary(empty($params['is_public'])?false:true);
        $voiceEntity->setPhoneLocation($params['phone_location']);
        $voiceEntity->setPhoneType($params['phone_type']);
        $voiceEntity->setVoiceMessageFile($params['voice_message_file']);

        $entityManager->persist($voiceEntity);
        $entityManager->flush();

    } else {
        $voiceEntity = $entityManager->getRepository('CRM\Voice\Entities\CivicrmVoiceBroadcast')->findOneBy(array('id' => $ids['mailing_id'] ));
        $voiceEntity->setName($params['name']);
        $voiceEntity->setIsPrimary(empty($params['is_public'])?false:true);
        $voiceEntity->setPhoneLocation($params['phone_location']);
        $voiceEntity->setPhoneType($params['phone_type']);
        $entityManager->persist($voiceEntity);
        $entityManager->flush();
    }



    $groupTableName = 'civicrm_group';

    foreach (array('groups', 'mailings') as $entity)
    {
      foreach (array('include', 'exclude', 'base') as $type)
      {

        if (isset($params[$entity]) &&
          CRM_Utils_Array::value($type, $params[$entity]) &&
          is_array($params[$entity][$type])) {
          foreach ($params[$entity][$type] as $entityId)
          {
            $voiceBroadCastGroupEntity = new \CRM\Voice\Entities\CivicrmVoiceBraodcastGroup();
            $voiceBroadCastGroupEntity->setVoiceId($voiceEntity->getId());
            $voiceBroadCastGroupEntity->setEntityTable(($groupTableName));
            $voiceBroadCastGroupEntity->setGroupType($type);
            $voiceBroadCastGroupEntity->setEntityId($entityId);
            $entityManager->persist($voiceBroadCastGroupEntity);
            $entityManager->flush();
          }
        }
      }
    }
//
//    if (!empty($params['search_id']) && !empty($params['group_id'])) {
//      $mg->reset();
//      $mg->mailing_id   = $mailing->id;
//      $mg->entity_table = $groupTableName;
//      $mg->entity_id    = $params['group_id'];
//      $mg->search_id    = $params['search_id'];
//      $mg->search_args  = $params['search_args'];
//      $mg->group_type   = 'Include';
//      $mg->save();
//    }

    // check and attach and files as needed
   // CRM_Core_BAO_File::processAttachment($params, 'civicrm_mailing', $mailing->id);

   // $transaction->commit();

    /**
     * create parent job if not yet created
     * condition on the existence of a scheduled date
     */
    if (!empty($params['scheduled_date']) && $params['scheduled_date'] != 'null')
    {
      $voiceBroadCastJob = $entityManager->getRepository('CRM\Voice\Entities\CivicrmVoiceBroadcastJob')->findOneBy(array('voice_id' => $voiceEntity->getId(),
                                                                                                                         'status'   => 'Scheduled',
                                                                                                                         'is_test' => false));

       if ( !empty($voiceBroadCastJob) ) {
          $voiceBroadCastJob->setScheduledDate(new \DateTime(strtotime($params['scheduled_date'])));
          $entityManager->persist($voiceBroadCastJob);
          $entityManager->flush();
      }


      // Populate the recipients.
     //$mailing->getRecipients($job->id, $mailing->id, NULL, NULL, TRUE, FALSE);
    }

    return $voiceEntity;
  }

}

