<?php

namespace CRM\Voice\DAO;


require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class Voice extends CRM_Core_DAO
{

 /**
  * static instance to hold the table name
  *
  * @var string
  * @static
  */
  static $_tableName = 'civicrm_voice_broadcast';

  /**
   * static instance to hold the field values
   *
   * @var array
   * @static
   */
  static $_fields = null;

  /**
   * static instance to hold the keys used in $_fields for each field.
   *
   * @var array
   * @static
   */
  static $_fieldKeys = null;

  /**
  * static instance to hold the FK relationships
  *
  * @var string
  * @static
  */
  static $_links = null;

  /**
   * static instance to hold the values that can
   * be imported
   *
   * @var array
   * @static
   */
  static $_import = null;

  /**
   * static instance to hold the values that can
   * be exported
   *
   * @var array
   * @static
   */
  static $_export = null;

  /**
   * static value to see if we should log any modifications to
   * this table in the civicrm_log table
   *
   * @var boolean
   * @static
   */
  static $_log = true;

  /**
   * Unique HRJob ID
   *
   * @var int unsigned
   */
  public $id;

 /**
  * voice campain name
  *
  * @var string
  */
  public $name;

 /**
  * Domain ID
  *
  * @var int unsigned
  */
  public $domain_id;

 /**
  * Campaign Id
  *
  * @var int unsigned
  */
  public $campaign_id;

 /**
  * Contact Id
  *
  * @var int unsigned
  */
  public $contact_id;

 /**
  * Phone Id
  *
  * @var int unsigned
  */
  public $phone_id;


 /**
  * Is Primary
  *
  * @var boolean
  */
  public $is_pramary;


 /**
  * Phone Location
  *
  * @var int unsigned
  */
  public $phone_location;

  /**
  * Phone type
  *
  * @var int unsigned
  */
  public $phone_type;

 /**
  * Is track call disposition
  *
  * @var boolean
  */
  public $is_track_call_disposition;

 /**
  * Is track call duration
  *
  * @var boolean
  */
  public $is_track_call_duration;


 /**
  * Is track call cost
  *
  * @var boolean
  */
  public $is_track_call_cost;


 /**
  * path of voice message file
  *
  * @var string
  */
  public $voice_message_file;


 /**
  * First day of the job
  *
  * @var datetime
  */
  public $created_at;



  public function __construct()
  {
    $this->__table = 'civicrm_voice_broadcast';
    parent::__construct();
  }
  /**
   * return foreign keys and entity references
   *
   * @static
   * @access public
   * @return array of CRM_Core_EntityReference
   */
  static function getReferenceColumns()
  {
    if (!self::$_links) {
      self::$_links = array(
        new CRM_Core_EntityReference(self::getTableName() , 'domain_id', 'civicrm_domain', 'id') ,
        new CRM_Core_EntityReference(self::getTableName() , 'contact_id', 'civicrm_contact', 'id') ,
        new CRM_Core_EntityReference(self::getTableName() , 'campain_id', 'civicrm_campain', 'id') ,
        new CRM_Core_EntityReference(self::getTableName() , 'phone_id', 'civicrm_contact', 'id') ,
      );
    }

    return self::$_links;
  }

  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  static function &fields()
  {
    if (!(self::$_fields)) {
        self::$_fields = array('id' => array('name'      => 'id',
                                             'type'      => CRM_Utils_Type::T_INT,
                                             'required' => true,
                                             ) ,
                              'namae' => array('name'           => 'name',
                                               'type'           => CRM_Utils_Type::T_STRING,
                                               'title'          => ts('Name') ,
                                                'maxlength'     => 100,
                                               'size'           => CRM_Utils_Type::HUGE,
                                               'export'         => true,
                                               'import'         => true,
                                               'where'          => 'civicrm_voice_broadcast.name',
                                               'headerPattern'  => '',
                                               'dataPattern'    => '',
                                             ) ,
                              'domain_id' => array('name'           => 'domain_id',
                                                   'type'           => CRM_Utils_Type::T_INT,
                                                   'title'          => ts('Domain ID') ,
                                                   'export'         => true,
                                                   'import'         => true,
                                                   'where'          => 'civicrm_voice_broadcast.domain_id',
                                                   'headerPattern'  => '',
                                                   'dataPattern'    => '',
                                                   'FKClassName'    => 'CRM_Contact_DAO_Domain',
                                                    ) ,
                              'campaign_id' => array('name'           => 'campaign_id',
                                                    'type'           => CRM_Utils_Type::T_INT,
                                                    'title'          => ts('Campain ID') ,
                                                    'export'         => true,
                                                    'import'         => true,
                                                    'where'          => 'civicrm_voice_broadcast.campaign_id',
                                                    'headerPattern'  => '',
                                                    'dataPattern'    => '',
                                                    'FKClassName'    => 'CRM_Contact_DAO_Campain',
                                                    ) ,
                              'contact_id' => array('name'          => 'contact_id',
                                                    'type'          => CRM_Utils_Type::T_INT,
                                                    'title'         => ts('Contact ID') ,
                                                    'export'        => true,
                                                    'import'        => true,
                                                    'where'         => 'civicrm_voice_broadcast.contact_id',
                                                    'headerPattern' => '',
                                                    'dataPattern'   => '',
                                                    'FKClassName'   => 'CRM_Contact_DAO_Contact',
                                                   ) ,
                              'phone_id' => array('name'           => 'phone_id',
                                                   'type'          => CRM_Utils_Type::T_INT,
                                                   'title'         => ts('Phone ID') ,
                                                   'export'        => true,
                                                   'import'        => true,
                                                   'where'         => 'civicrm_voice_broadcast.phone_id',
                                                   'headerPattern' => '',
                                                   'dataPattern'   => '',
                                                   ) ,

                              'is_primary' => array('name'          => 'is_primary',
                                                    'type'          => CRM_Utils_Type::T_BOOLEAN,
                                                    'title'         => ts('Phone type Is Primary') ,
                                                    'export'        => true,
                                                    'import'        => true,
                                                    'where'         => 'civicrm_voice_broadcast.is_primary',
                                                    'headerPattern' => '',
                                                    'dataPattern'   => '',
                                                    ),

                              'phone_location' => array('name'          => 'phone_location',
                                                        'type'          => CRM_Utils_Type::T_INT,
                                                        'title'         => ts('Phone Location') ,
                                                        'export'        => true,
                                                        'import'        => true,
                                                        'where'         => 'civicrm_voice_broadcast.phone_id',
                                                        'headerPattern' => '',
                                                        'dataPattern'   => '',
                                                   ) ,
                              'phone_type' => array('name'          => 'phone_type',
                                                    'type'          => CRM_Utils_Type::T_INT,
                                                    'title'         => ts('Phone type') ,
                                                    'export'        => true,
                                                    'import'        => true,
                                                    'where'         => 'civicrm_voice_broadcast.phone_type',
                                                    'headerPattern' => '',
                                                    'dataPattern'   => '',
                                                   ) ,
                              'is_track_call_disposition' => array('name'          => 'is_track_call_disposition',
                                                                   'type'          => CRM_Utils_Type::T_INT,
                                                                   'title'         => ts('Is track call disposition') ,
                                                                   'export'        => true,
                                                                   'import'        => true,
                                                                   'where'         => 'civicrm_voice_broadcast.is_track_call_disposition',
                                                                   'headerPattern' => '',
                                                                   'dataPattern'   => '',
                                                                    ) ,
                              'is_track_call_duration' => array('name'           => 'is_track_call_duration',
                                                                'type'           => CRM_Utils_Type::T_INT,
                                                                 'title'         => ts('Is track call duration') ,
                                                                 'export'        => true,
                                                                 'import'        => true,
                                                                 'where'         => 'civicrm_voice_broadcast.is_track_call_duration',
                                                                 'headerPattern' => '',
                                                                 'dataPattern'   => '',
                                                                 ) ,
                              'is_track_call_cost' => array('name'          => 'is_track_call_cost',
                                                            'type'          => CRM_Utils_Type::T_INT,
                                                            'title'         => ts('Is track call cost') ,
                                                            'export'        => true,
                                                            'import'        => true,
                                                            'where'         => 'civicrm_voice_broadcast.is_track_call_cost',
                                                            'headerPattern' => '',
                                                            'dataPattern'   => '',
                                                            ) ,
                              'voice_message_file' => array('name'          => 'voice_message_file',
                                                           'type'           => CRM_Utils_Type::T_STRING,
                                                           'title'          => ts('Voice Message File') ,
                                                           'maxlength'      => 300,
                                                           'size'           => CRM_Utils_Type::HUGE,
                                                           'export'         => true,
                                                           'import'         => true,
                                                           'where'          => 'civicrm_voice_broadcast.voice_message_file',
                                                           'headerPattern'  => '',
                                                           'dataPattern'    => '',
                                                           ),
                              'created_at' => array('name'          => 'created_at',
                                                    'type'          => CRM_Utils_Type::T_DATETIME,
                                                    'title'         => ts('Job End Date') ,
                                                    'export'        => true,
                                                    'import'        => true,
                                                    'where'         => 'civicrm_voice_broadcast.created_at',
                                                    'headerPattern' => '',
                                                    'dataPattern'   => '',
                                                   ) ,
                              );

    }

    return self::$_fields;
  }


  /**
   * returns the names of this table
   *
   * @access public
   * @static
   * @return string
   */
  static function getTableName()
  {
    return self::$_tableName;
  }

  /**
   * returns if this table needs to be logged
   *
   * @access public
   * @return boolean
   */
  function getLog()
  {
    return self::$_log;
  }

  /**
   * returns the list of fields that can be imported
   *
   * @access public
   * return array
   * @static
   */
  static function &import($prefix = false)
  {
    if (!(self::$_import)) {
      self::$_import = array();
      $fields = self::fields();
      foreach($fields as $name => $field) {
        if (!empty($field['import'])) {
          if ($prefix) {
            self::$_import['voice'] = & $fields[$name];
          } else {
            self::$_import[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_import;
  }

  /**
   *    returns the list of fields that can be exported
   *
   * @access public
   * return array
   * @static
   */
  static function &export($prefix = false)
  {
    if (!(self::$_export)) {
      self::$_export = array();
      $fields = self::fields();
      foreach($fields as $name => $field) {
        if (!empty($field['export'])) {
          if ($prefix) {
            self::$_export['voice'] = & $fields[$name];
          } else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }
}