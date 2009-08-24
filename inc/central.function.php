<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

/**
 * Show the central global view
 *
 *
 **/
function showCentralGlobalView() {
   global $CFG_GLPI,$LANG;

   $showticket=haveRight("show_all_ticket","1");

   echo "<table class='tab_cadre_central'><tr>";
   echo "<td class='top'><br>";
   echo "<table >";
   if ($showticket) {
      echo "<tr><td class='top' width='450px'>";
      showCentralJobCount();
      echo "</td></tr>";
   }
   if (haveRight("contract","r")) {
      echo "<tr><td class='top' width='450px'>";
      showCentralContract();
      echo "</td></tr>";
   }
   echo "</table></td>";

   if (haveRight("logs","r")) {
      echo "<td class='top' width='450px'>";

      //Show last add events
      showAddEvents($_SERVER['PHP_SELF'],$_SESSION["glpiname"]);

      echo "</td>";
   }
   echo "</tr></table>";

   if ($_SESSION["glpishow_jobs_at_login"] && $showticket) {
      echo "<br>";
      showNewJobList();
   }
}

/**
 * Show the central personal view
 *
 *
 **/
function showCentralMyView() {
      global $LANG, $DB;

      $showticket=(haveRight("show_all_ticket","1") || haveRight("show_assign_ticket","1"));
      echo "<table class='tab_cadre_central'>";

      if ($DB->isSlave() && !$DB->first_connection) {
         echo "<tr><th colspan='2'><br>";
         displayTitle(GLPI_ROOT."/pics/warning.png", $LANG['setup'][809], $LANG['setup'][809]);
         echo "</th></tr>";
      }
      echo "<tr><td class='top'><table>";

      if ($showticket) {
         echo "<tr><td class='top' width='450px'><br>";
         showCentralJobList($_SERVER['PHP_SELF'],0,"process",false);
         echo "</td></tr>";
         echo "<tr><td class='top' width='450px'>";
         showCentralJobList($_SERVER['PHP_SELF'],0,"waiting",false);
         echo "</td></tr>";
      }
      echo "</table></td>";
      echo "<td class='top'><table><tr>";
      echo "<td class='top' width='450px'><br>";
      showPlanningCentral($_SESSION["glpiID"]);
      echo "</td></tr>";

      echo "<tr><td class='top' width='450px'>";
      showCentralReminder();
      echo "</td></tr>";

      if (haveRight("reminder_public","r")) {
         echo "<tr><td class='top' width='450px'>";
         showCentralReminder($_SESSION["glpiactive_entity"]);
         $entities=array_reverse(getAncestorsOf("glpi_entities",$_SESSION["glpiactive_entity"]));
         foreach ($entities as $entity) {
            showCentralReminder($entity, true);
         }
         foreach ($_SESSION["glpiactiveentities"] as $entity) {
            if ($entity != $_SESSION["glpiactive_entity"]) {
               showCentralReminder($entity, false);
            }
         }
         echo "</td></tr>";
      }
      echo "</table></td></tr></table>";
}

/**
 * Show the central group view
 *
 *
 **/
function showCentralGroupView() {

      $showticket=haveRight("show_all_ticket","1") || haveRight("show_assign_ticket","1");

      echo "<table class='tab_cadre_central'>";
      echo "<tr><td class='top'><table>";

      if ($showticket) {
         echo "<tr><td class='top' width='450px'><br>";
         showCentralJobList($_SERVER['PHP_SELF'],0,"process",true);
         echo "</td></tr>";
      }
      echo "</table></td>";
      echo "<td class='top'><table>";

      if ($showticket) {
         echo "<tr><td  class='top' width='450px'><br>";
         showCentralJobList($_SERVER['PHP_SELF'],0,"waiting",true);
         echo "</td></tr>";
      }
      echo "</table></td></tr></table>";
}

?>