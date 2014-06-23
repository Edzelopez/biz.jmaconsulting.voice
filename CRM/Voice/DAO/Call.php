<?php

namespace CRM\Voice\DAO;


require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class Call extends CRM_Core_DAO
{

 /**
  * static instance to hold the table name
  *
  * @var string
  * @static
  */
  static $_tableName = 'civicrm_voice_broadcast_call';

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
  * Domain ID
  *
  * @var int unsigned
  */
  public $job_id;



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
  * Track call disposition
  *
  * @var string
  */
  public $disposition;

 /**
  * Track call duration
  *
  * @var string
  */
  public $duration;


 /**
  * Track call cost
  *
  * @var string
  */
  public $cost;



 /**
  * First day of the job
  *
  * @var datetime
  */
  public $created_at;



  public function __construct()
  {
    $this->__table = 'civicrm_voice_broadcast_call';
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
        new CRM_Core_EntityReference(self::getTableName() , 'contact_id', 'civicrm_contact', 'id') ,
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
                              'job_id' => array('name'           => 'domain_id',
                                                   'type'           => CRM_Utils_Type::T_INT,
                                                   'title'          => ts('Domain ID') ,
                                                   'export'         => true,
                                                   'import'         => true,
                                                   'where'          => 'civicrm_voice_broadcast.job_id',
                                                   'headerPattern'  => '',
                                                   'dataPattern'    => '',
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
                                                   ),
                              'disposition' => array('name'          => 'disposition',
                                                     'type'           => CRM_Utils_Type::T_STRING,
                                                     'title'          => ts('Disposition') ,
                                                     'maxlength'      => 10,
                                                     'size'           => CRM_Utils_Type::HUGE,
                                                     'export'         => true,
                                                     'import'         => true,
                                                     'where'          => 'civicrm_voice_broadcast.disposition',
                                                     'headerPattern'  => '',
                                                     'dataPattern'    => '',
                                                    ),
                              'duration' => array('name'           => 'duration',
                                                  'type'           => CRM_Utils_Type::T_STRING,
                                                  'title'          => ts('Duration') ,
                                                  'maxlength'      => 10,
                                                  'size'           => CRM_Utils_Type::HUGE,
                                                  'export'         => true,
                                                  'import'         => true,
                                                  'where'          => 'civicrm_voice_broadcast.duration',
                                                  'headerPattern'  => '',
                                                  'dataPattern'    => '',
                                                 ),
                              'call' => array('name'           => 'call',
                                              'type'           => CRM_Utils_Type::T_STRING,
                                              'title'          => ts('call') ,
                                              'maxlength'      => 300,
                                              'size'           => CRM_Utils_Type::HUGE,
                                              'export'         => true,
                                              'import'         => true,
                                              'where'          => 'civicrm_voice_broadcast.call',
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