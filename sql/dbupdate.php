<#1>
<?php

use srag\Plugins\Hub2\Log\Log;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Origin\User\ARUserOrigin;
use srag\Plugins\Hub2\Origin\CourseMembership\ARCourseMembershipOrigin;
use srag\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Config\ArConfigOld;

ARUserOrigin::updateDB();
ARUser::updateDB();
ARCourse::updateDB();
ARCourseMembership::updateDB();
ARCategory::updateDB();
ARSession::updateDB();
ARGroup::updateDB();
ARGroupMembership::updateDB();
ARSessionMembership::updateDB();
?>
<#2>
<?php
global $DIC;
$database = $DIC->database();
$database->modifyTableColumn(ARCourseMembership::TABLE_NAME, 'ilias_id', ['type' => 'text', 'length' => 256]);
$database->modifyTableColumn(ARSessionMembership::TABLE_NAME, 'ilias_id', ['type' => 'text', 'length' => 256]);
$database->modifyTableColumn(ARGroupMembership::TABLE_NAME, 'ilias_id', ['type' => 'text', 'length' => 256]);
?>
<#3>
<?php
AROrgUnit::updateDB();
AROrgUnitMembership::updateDB();
?>
<#4>
<?php
ArConfig::updateDB();

global $DIC;
$database = $DIC->database();

if ($database->tableExists(ArConfigOld::TABLE_NAME)) {
    ArConfigOld::updateDB();

    foreach (ArConfigOld::get() as $config) {
        /**
         * @var ArConfigOld $config
         */
        switch ($config->getKey()) {
            case 0:
                // some installations seem to have an empty record with the key 0
                break;
            default:
                ArConfig::setField(strval($config->getKey()), $config->getValue());
                break;
        }
    }

    $database->dropTable(ArConfigOld::TABLE_NAME);
}
?>
<#5>
<?php
$administration_role_ids = json_encode(ArConfig::getField(ArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS));
if (strpos($administration_role_ids, '[') === false) {
    $administration_role_ids = preg_split('/, */', $administration_role_ids);
    $administration_role_ids = array_map(function (string $id): int {
        return intval($id);
    }, $administration_role_ids);

    ArConfig::setField(
        ArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS,
        $administration_role_ids
    );
}

?>
<#6>
<?php
/* */
?>
<#7>
<?php
/* */
?>
<#8>
<?php
Log::updateDB();
?>
<#9>
<?php
ARUserOrigin::updateDB();
ARUser::updateDB();
ARCourse::updateDB();
ARCourseMembership::updateDB();
ARCategory::updateDB();
ARSession::updateDB();
ARGroup::updateDB();
ARGroupMembership::updateDB();
ARSessionMembership::updateDB();
AROrgUnit::updateDB();
AROrgUnitMembership::updateDB();
?>
<#10>
<?php
Log::updateDB();
?>
<#11>
<?php
ARCourseMembershipOrigin::updateDB();
?>
<#12>
<?php
Log::updateDB();
?>
<#13>
<?php
ARUserOrigin::updateDB();
?>
<#14>
<?php

$i = 1;
foreach ((new OriginFactory())->getAllActive() as $origin) {
    /**
     * @var IOrigin $origin
     */
    $origin->setSort($i);

    $origin->store();

    $i++;
}
?>
<#15>
<?php
global $DIC;
$database = $DIC->database();

$database->modifyTableColumn(
    Log::TABLE_NAME,
    'object_ext_id',
    [
        'type' => 'text',
        'length' => 255
    ]
);
?>
<#16>
<?php
Log::updateDB();
?>