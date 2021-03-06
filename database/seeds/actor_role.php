<?php
/**
 * Export to PHP Array plugin for PHPMyAdmin
 * @version 4.6.6deb5
 */

/**
 * Database `phpipv2`
 */

/* `phpipv2`.`actor_role` */
$actor_role = array(
  array('code' => 'AGT','name' => 'Primary Agent','display_order' => '20','shareable' => '0','show_ref' => '1','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '3','box_color' => NULL,'notes' => NULL,'creator' => 'root','updated' => '2016-03-16 16:49:49','updater' => NULL),
  array('code' => 'AGT2','name' => 'Secondary Agent','display_order' => '22','shareable' => '0','show_ref' => '1','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '3','box_color' => NULL,'notes' => 'Usually the primary agent\'s agent','creator' => 'root','updated' => '2011-05-05 11:17:57','updater' => NULL),
  array('code' => 'ANN','name' => 'Annuity Agent','display_order' => '21','shareable' => '0','show_ref' => '1','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '3','box_color' => NULL,'notes' => 'Agent in charge of renewals. "Client handled" is a special agent who, when added, will delete any renewals in the matter','creator' => 'root','updated' => '2016-03-16 16:49:49','updater' => NULL),
  array('code' => 'APP','name' => 'Applicant','display_order' => '3','shareable' => '1','show_ref' => '1','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '1','box_color' => NULL,'notes' => 'Assignee in the US, i.e. the owner upon filing','creator' => 'root','updated' => '2016-03-16 16:49:49','updater' => NULL),
  array('code' => 'CLI','name' => 'Client','display_order' => '1','shareable' => '1','show_ref' => '1','show_company' => '0','show_rate' => '1','show_date' => '0','box' => '1','box_color' => NULL,'notes' => 'The client we take instructions from and who we invoice. DO NOT CHANGE OR DELETE: this is also a database user role','creator' => 'root','updated' => '2016-04-03 15:56:28','updater' => NULL),
  array('code' => 'CNT','name' => 'Contact','display_order' => '30','shareable' => '1','show_ref' => '1','show_company' => '1','show_rate' => '0','show_date' => '0','box' => '4','box_color' => NULL,'notes' => 'Client\'s contact person','creator' => 'root','updated' => '2011-05-05 11:19:26','updater' => NULL),
  array('code' => 'DEL','name' => 'Delegate','display_order' => '31','shareable' => '1','show_ref' => '0','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '4','box_color' => NULL,'notes' => 'Another user allowed to manage the case','creator' => 'root','updated' => '2016-03-16 16:49:50','updater' => NULL),
  array('code' => 'FAGT','name' => 'Former Agent','display_order' => '23','shareable' => '0','show_ref' => '1','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '127','box_color' => '#000000','notes' => NULL,'creator' => 'root','updated' => '2012-08-06 11:03:14','updater' => NULL),
  array('code' => 'FOWN','name' => 'Former Owner','display_order' => '5','shareable' => '0','show_ref' => '0','show_company' => '0','show_rate' => '0','show_date' => '1','box' => '1','box_color' => NULL,'notes' => 'To keep track of ownership history','creator' => 'root','updated' => '2016-03-16 16:49:50','updater' => NULL),
  array('code' => 'INV','name' => 'Inventor','display_order' => '10','shareable' => '1','show_ref' => '0','show_company' => '1','show_rate' => '0','show_date' => '0','box' => '2','box_color' => NULL,'notes' => NULL,'creator' => 'root','updated' => '2016-03-16 16:49:50','updater' => NULL),
  array('code' => 'LCN','name' => 'Licensee','display_order' => '127','shareable' => '0','show_ref' => '0','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '0','box_color' => NULL,'notes' => NULL,'creator' => 'root','updated' => '2016-03-16 16:49:50','updater' => NULL),
  array('code' => 'OFF','name' => 'Patent Office','display_order' => '127','shareable' => '0','show_ref' => '0','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '0','box_color' => NULL,'notes' => NULL,'creator' => 'root','updated' => '2016-03-16 16:49:50','updater' => NULL),
  array('code' => 'OPP','name' => 'Opposing Party','display_order' => '127','shareable' => '0','show_ref' => '0','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '0','box_color' => NULL,'notes' => NULL,'creator' => 'root','updated' => '2016-03-16 16:49:50','updater' => NULL),
  array('code' => 'OWN','name' => 'Owner','display_order' => '4','shareable' => '0','show_ref' => '1','show_company' => '0','show_rate' => '1','show_date' => '1','box' => '1','box_color' => 'NULL','notes' => 'Use if different than applicant','creator' => 'root','updated' => '2012-12-20 11:13:50','updater' => NULL),
  array('code' => 'PAY','name' => 'Payor','display_order' => '2','shareable' => '1','show_ref' => '0','show_company' => '0','show_rate' => '1','show_date' => '0','box' => '1','box_color' => '#000000','notes' => 'The actor who pays','creator' => 'root','updated' => '2011-09-27 12:18:42','updater' => NULL),
  array('code' => 'PTNR','name' => 'Partner','display_order' => '127','shareable' => '1','show_ref' => '0','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '0','box_color' => NULL,'notes' => NULL,'creator' => 'root','updated' => '2016-03-16 16:49:50','updater' => NULL),
  array('code' => 'TRA','name' => 'Translator','display_order' => '127','shareable' => '0','show_ref' => '0','show_company' => '0','show_rate' => '0','show_date' => '1','box' => '127','box_color' => '#000000','notes' => NULL,'creator' => 'root','updated' => '2013-12-16 12:47:04','updater' => NULL),
  array('code' => 'WRI','name' => 'Writer','display_order' => '127','shareable' => '1','show_ref' => '0','show_company' => '0','show_rate' => '0','show_date' => '0','box' => '0','box_color' => NULL,'notes' => 'Person who follows the case','creator' => 'root','updated' => '2016-03-16 16:49:50','updater' => NULL)
);
