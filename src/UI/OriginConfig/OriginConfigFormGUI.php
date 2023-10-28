<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\UI\OriginConfig;

use hub2ConfigOriginsGUI;
use hub2MainGUI;
use ilCheckboxInputGUI;
use ilFormSectionHeaderGUI;
use ilHiddenInputGUI;
use ilHub2Plugin;
use ilNonEditableValueGUI;
use ilNumberInputGUI;
use ilPropertyFormGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilRepositorySelector2InputGUI;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\CourseMembership\ICourseMembershipOrigin;
use srag\Plugins\Hub2\Origin\Group\IGroupOrigin;
use srag\Plugins\Hub2\Origin\GroupMembership\IGroupMembershipOrigin;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginRepository;
use srag\Plugins\Hub2\Origin\Properties\DTOPropertyParser;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;
use srag\Plugins\Hub2\Origin\Session\ISessionOrigin;
use srag\Plugins\Hub2\Origin\SessionMembership\ISessionMembershipOrigin;


/**
 * Class OriginConfigFormGUI
 * @package      srag\Plugins\Hub2\UI\OriginConfig
 * @author       Stefan Wanzenried <sw@studer-raimann.ch>
 * @author       Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginConfigFormGUI extends ilPropertyFormGUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    public const POST_VAR_ADHOC = "adhoc";
    public const POST_VAR_SORT = "sort";
    protected hub2ConfigOriginsGUI $parent_gui;
    /**
     * @var IOrigin
     */
    protected IOrigin $origin;
    /**
     * @var IOriginRepository
     */
    protected IOriginRepository $originRepository;

    /**
     * @param hub2ConfigOriginsGUI $parent_gui
     * @param IOriginRepository    $originRepository
     * @param IOrigin              $origin
     */
    public function __construct(hub2ConfigOriginsGUI $parent_gui, IOriginRepository $originRepository, IOrigin $origin)
    {

        $this->parent_gui = $parent_gui;
        $this->origin = $origin;
        $this->originRepository = $originRepository;
        $this->initForm();
        if (!$origin->getId()) {
            $this->addCommandButton(hub2ConfigOriginsGUI::CMD_CREATE_ORIGIN, ilHub2Plugin::getInstance()->txt('button_save'));
            $this->setTitle(ilHub2Plugin::getInstance()->txt('origin_form_title_add'));
        } else {
            $this->addCommandButton(hub2ConfigOriginsGUI::CMD_SAVE_ORIGIN, ilHub2Plugin::getInstance()->txt('button_save'));
            $this->setTitle(ilHub2Plugin::getInstance()->txt('origin_form_title_edit'));
        }
        $this->addCommandButton(hub2ConfigOriginsGUI::CMD_CANCEL, ilHub2Plugin::getInstance()->txt('button_cancel'));
        parent::__construct();
        $this->setFormAction($this->ctrl->getFormAction($this->parent_gui));
    }

    /**
     *
     */
    protected function initForm()
    {
        $this->addGeneral();
        if ($this->origin->getId()) {
            $this->addConnectionConfig();
            $this->addSyncConfig();
            $this->addNotificationConfig();

            // Properties for object status: NEW, UPDATE, DELETE
            $header = new ilFormSectionHeaderGUI();
            $header->setTitle(ilHub2Plugin::getInstance()->txt(
                'common_on_status',
                "",
                [ilHub2Plugin::getInstance()->txt('common_on_status_new')]
            ));
            $this->addItem($header);
            $this->addPropertiesNew();

            $header = new ilFormSectionHeaderGUI();
            $header->setTitle(ilHub2Plugin::getInstance()->txt(
                'common_on_status',
                "",
                [ilHub2Plugin::getInstance()->txt('common_on_status_update')]
            ));
            $this->addItem($header);
            $this->addPropertiesUpdate();

            $header = new ilFormSectionHeaderGUI();
            $header->setTitle(ilHub2Plugin::getInstance()->txt(
                'common_on_status',
                "",
                [ilHub2Plugin::getInstance()->txt('common_on_status_delete')]
            ));
            $this->addItem($header);
            $this->addPropertiesDelete();
        }
    }

    /**
     *
     */
    protected function addPropertiesNew()
    {
    }

    /**
     * By default, this method parses the DTO objects and presents a checkbox for each DTO property,
     * meaning if this property should be updated on the user object, e.g. should the firstname of
     * a user be updated?
     * Subclasses using static properties should overwrite this method, add the static properties
     * and call parent::addPropertiesUpdate() at the very end
     */
    protected function addPropertiesUpdate()
    {
        $ucfirst = ucfirst($this->origin->getObjectType());
        $parser = new DTOPropertyParser("srag\\Plugins\\Hub2\\Object\\{$ucfirst}\\{$ucfirst}DTO");
        foreach ($parser->getProperties() as $property) {
            $postVar = IOriginProperties::PREFIX_UPDATE_DTO . $property->name;
            $title = ilHub2Plugin::getInstance()->txt('origin_form_field_update_dto', "", [ucfirst($property->name)]);
            $cb = new ilCheckboxInputGUI($title, $this->prop($postVar));
            if ($property->descriptionKey) {
                $cb->setInfo(ilHub2Plugin::getInstance()->txt($property->descriptionKey));
            }
            $cb->setChecked($this->origin->properties()->updateDTOProperty($property->name));
            $this->addItem($cb);
        }
    }

    /**
     *
     */
    protected function addPropertiesDelete()
    {
    }

    /**
     *
     */
    protected function addNotificationConfig()
    {
        $h = new ilFormSectionHeaderGUI();
        $h->setTitle(ilHub2Plugin::getInstance()->txt('origin_form_header_notification'));
        $this->addItem($h);
        $te = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('origin_form_field_summary_email'),
            $this->conf(IOriginConfig::NOTIFICATION_SUMMARY)
        );
        $te->setValue(implode(',', $this->origin->config()->getNotificationsSummary()));
        $te->setInfo(ilHub2Plugin::getInstance()->txt('origin_form_comma_separated'));
        $this->addItem($te);
        $te = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('origin_form_field_notification_email'),
            $this->conf(IOriginConfig::NOTIFICATION_ERRORS)
        );
        $te->setValue(implode(',', $this->origin->config()->getNotificationsErrors()));
        $te->setInfo(ilHub2Plugin::getInstance()->txt('origin_form_comma_separated'));
        $this->addItem($te);
    }

    /**
     *
     */
    protected function addConnectionConfig()
    {
        $header = new ilFormSectionHeaderGUI();
        $header->setTitle(ilHub2Plugin::getInstance()->txt('origin_form_header_connection'));
        $this->addItem($header);
        $ro = new ilRadioGroupInputGUI(
            ilHub2Plugin::getInstance()->txt('origin_form_field_conf_type'),
            $this->conf(IOriginConfig::CONNECTION_TYPE)
        );
        $ro->setValue($this->origin->config()->getConnectionType());
        {
            $db = new ilRadioOption(
                ilHub2Plugin::getInstance()->txt('origin_form_field_conf_type_path'),
                IOriginConfig::CONNECTION_TYPE_PATH,
                ilHub2Plugin::getInstance()
                                                         ->translate('origin_form_field_conf_type_path_info')
            );
            {
                $te = new ilTextInputGUI(
                    ilHub2Plugin::getInstance()->txt('origin_form_field_conf_type_path_path'),
                    $this->conf(IOriginConfig::PATH)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::PATH));
                $db->addSubItem($te);
            }
            $ro->addOption($db);
            $file = new ilRadioOption(
                ilHub2Plugin::getInstance()
                                          ->translate('origin_form_field_conf_type_db'),
                IOriginConfig::CONNECTION_TYPE_SERVER,
                ilHub2Plugin::getInstance()
                                                           ->translate('origin_form_field_conf_type_db_info')
            );
            {
                $te = new ilTextInputGUI(
                    ilHub2Plugin::getInstance()->txt('origin_form_field_conf_type_db_host'),
                    $this->conf(IOriginConfig::SERVER_HOST)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_HOST));
                $file->addSubItem($te);
                $te = new ilTextInputGUI(
                    ilHub2Plugin::getInstance()->txt('origin_form_field_conf_type_db_port'),
                    $this->conf(IOriginConfig::SERVER_PORT)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_PORT));
                $file->addSubItem($te);
                $te = new ilTextInputGUI(
                    ilHub2Plugin::getInstance()
                                             ->translate('origin_form_field_conf_type_db_username'),
                    $this->conf(IOriginConfig::SERVER_USERNAME)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_USERNAME));
                $file->addSubItem($te);
                $te = new ilTextInputGUI(
                    ilHub2Plugin::getInstance()
                                             ->translate('origin_form_field_conf_type_db_password'),
                    $this->conf(IOriginConfig::SERVER_PASSWORD)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_PASSWORD));
                $file->addSubItem($te);
                $te = new ilTextInputGUI(
                    ilHub2Plugin::getInstance()
                                             ->translate('origin_form_field_conf_type_db_database'),
                    $this->conf(IOriginConfig::SERVER_DATABASE)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_DATABASE));
                $file->addSubItem($te);
                $te = new ilTextInputGUI(
                    ilHub2Plugin::getInstance()
                                             ->translate('origin_form_field_conf_type_db_search_base'),
                    $this->conf(IOriginConfig::SERVER_SEARCH_BASE)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_SEARCH_BASE));
                $file->addSubItem($te);
            }
            $ro->addOption($file);
            $external = new ilRadioOption(
                ilHub2Plugin::getInstance()
                                              ->translate('origin_form_field_conf_type_external'),
                IOriginConfig::CONNECTION_TYPE_EXTERNAL,
                ilHub2Plugin::getInstance()
                                                             ->translate('origin_form_field_conf_type_external_info')
            );
            $ro->addOption($external);

            $ilias_file = new ilRadioOption(
                ilHub2Plugin::getInstance()
                                                ->translate('origin_form_field_conf_type_ilias_file'),
                IOriginConfig::CONNECTION_TYPE_ILIAS_FILE,
                ilHub2Plugin::getInstance()
                                                               ->translate('origin_form_field_conf_type_ilias_file_info')
            );
            $ilias_file->addSubItem($this->getILIASFileRepositorySelector());
            $ro->addOption($ilias_file);
        }
        $this->addItem($ro);
    }

    /**
     * @return ilRepositorySelector2InputGUI
     */
    public function getILIASFileRepositorySelector(): ilRepositorySelector2InputGUI
    {
        $this->ctrl->setParameterByClass(
            hub2MainGUI::class,
            hub2ConfigOriginsGUI::ORIGIN_ID,
            $this->origin->getId()
        );

        $ilias_file_selector = new ilRepositorySelector2InputGUI(
            ilHub2Plugin::getInstance()
                                                                     ->translate("origin_form_field_conf_type_ilias_file"),
            $this->conf(IOriginConfig::ILIAS_FILE_REF_ID)
        );

        $ilias_file_selector->getExplorerGUI()->setSelectableTypes(["file"]);

        $ilias_file_selector->setValue($this->origin->config()->get(IOriginConfig::ILIAS_FILE_REF_ID));

        return $ilias_file_selector;
    }

    /**
     *
     */
    protected function addSyncConfig()
    {
        $h = new ilFormSectionHeaderGUI();
        $h->setTitle(ilHub2Plugin::getInstance()->txt('origin_form_header_sync'));
        $this->addItem($h);

        $te = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('origin_form_field_class_name'),
            'implementation_class_name'
        );
        $te->setInfo(nl2br(ilHub2Plugin::getInstance()
                               ->translate(
                                   'origin_form_field_class_name_info',
                                   "",
                                   [ArConfig::getField(ArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH)]
                               ), false));
        $te->setValue($this->origin->getImplementationClassName());
        $te->setRequired(true);
        $this->addItem($te);

        $te = new ilTextInputGUI(ilHub2Plugin::getInstance()->txt('origin_form_field_namespace'), 'implementation_namespace');
        $te->setInfo(ilHub2Plugin::getInstance()->txt('origin_form_field_namespace_info'));
        $te->setValue($this->origin->getImplementationNamespace());
        $te->setRequired(true);
        $this->addItem($te);

        $se = new ilSelectInputGUI(
            ilHub2Plugin::getInstance()->txt('com_prop_link_to_origin'),
            $this->conf(IOriginConfig::LINKED_ORIGIN_ID)
        );
        $options = ['' => ''];
        foreach ($this->originRepository->all() as $origin) {
            if ($origin->getId() == $this->origin->getId()) {
                continue;
            }
            $options[$origin->getId()] = $origin->getTitle();
        }
        $se->setOptions($options);
        $se->setValue($this->origin->config()->getLinkedOriginId());
        $this->addItem($se);

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('com_prop_check_amount'),
            $this->conf(IOriginConfig::CHECK_AMOUNT)
        );
        $cb->setInfo(ilHub2Plugin::getInstance()->txt('com_prop_check_amount_info'));
        $cb->setChecked($this->origin->config()->getCheckAmountData());

        $se = new ilSelectInputGUI(
            ilHub2Plugin::getInstance()
                                       ->translate('com_prop_check_amount_percentage'),
            $this->conf(IOriginConfig::CHECK_AMOUNT_PERCENTAGE)
        );
        $options = [];
        for ($i = 10; $i <= 100; $i += 10) {
            $options[$i] = "$i%";
        }
        $se->setOptions($options);
        $se->setValue($this->origin->config()->getCheckAmountDataPercentage());
        $cb->addSubItem($se);
        $this->addItem($cb);

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('com_prop_shortlink'),
            $this->conf(IOriginConfig::SHORT_LINK)
        );
        $cb->setChecked($this->origin->config()->useShortLink());
        $subcb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('com_prop_force_login'),
            $this->conf(IOriginConfig::SHORT_LINK_FORCE_LOGIN)
        );
        $subcb->setChecked($this->origin->config()->useShortLinkForcedLogin());
        $cb->addSubItem($subcb);
        $this->addItem($cb);

        $te = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('origin_from_field_active_period'),
            $this->conf(IOriginConfig::ACTIVE_PERIOD)
        );
        $te->setInfo(ilHub2Plugin::getInstance()->txt('origin_from_field_active_period_info'));
        $te->setValue($this->origin->config()->getActivePeriod());
        $this->addItem($te);
    }

    /**
     *
     */
    protected function addGeneral()
    {
        if ($this->origin->getId()) {
            $item = new ilNonEditableValueGUI();
            $item->setTitle(ilHub2Plugin::getInstance()->txt("origin_id"));
            $item->setValue($this->origin->getId());
            $this->addItem($item);
            $item = new ilHiddenInputGUI('origin_id');
            $item->setValue($this->origin->getId());
            $this->addItem($item);

            $item = new ilNumberInputGUI(ilHub2Plugin::getInstance()->txt("origin_sort"), self::POST_VAR_SORT);
            $item->setValue($this->origin->getSort());
            $this->addItem($item);
        }
        $item = new ilTextInputGUI(ilHub2Plugin::getInstance()->txt('origin_title'), 'title');
        $item->setValue($this->origin->getTitle());
        $item->setRequired(true);
        $this->addItem($item);
        $item = new ilTextAreaInputGUI(ilHub2Plugin::getInstance()->txt('origin_description'), 'description');
        $item->setValue($this->origin->getDescription());
        $item->setRequired(true);
        $this->addItem($item);
        if ($this->origin->getId()) {
            $item = new ilNonEditableValueGUI();
            $item->setTitle(ilHub2Plugin::getInstance()->txt('origin_form_field_usage_type'));
            $item->setValue(ilHub2Plugin::getInstance()->txt("origin_object_type_" . $this->origin->getObjectType()));
            $this->addItem($item);
            $item = new ilCheckboxInputGUI(ilHub2Plugin::getInstance()->txt("origin_form_field_adhoc"), self::POST_VAR_ADHOC);
            $item->setChecked($this->origin->isAdHoc());
            $item->setInfo(ilHub2Plugin::getInstance()->txt("origin_form_field_adhoc_info"));

            if ($this->hasOriginAdHocParentScope()) {
                $subitem = new ilCheckboxInputGUI(
                    ilHub2Plugin::getInstance()->txt("origin_form_field_adhoc_parent_scope"),
                    "adhoc_parent_scope"
                );
                $subitem->setChecked($this->origin->isAdhocParentScope());
                $subitem->setInfo(ilHub2Plugin::getInstance()->txt("origin_form_field_adhoc_parent_scope_info"));
                $item->addSubItem($subitem);
            }

            $this->addItem($item);
            $item = new ilCheckboxInputGUI(ilHub2Plugin::getInstance()->txt('origin_form_field_active'), 'active');
            $item->setChecked($this->origin->isActive());
            $this->addItem($item);
        } else {
            $item = new ilSelectInputGUI(ilHub2Plugin::getInstance()->txt('origin_form_field_usage_type'), 'object_type');
            $item->setRequired(true);
            $options = [];
            foreach (AROrigin::$object_types as $type) {
                $options[$type] = ilHub2Plugin::getInstance()->txt('origin_object_type_' . $type);
            }
            $item->setOptions($options);
            $this->addItem($item);
        }
    }

    /**
     * @return bool
     */
    protected function hasOriginAdHocParentScope(): bool
    {
        switch (true) {
            case $this->origin instanceof ICourseMembershipOrigin:
            case $this->origin instanceof IGroupOrigin:
            case $this->origin instanceof IGroupMembershipOrigin:
            case $this->origin instanceof ISessionOrigin:
            case $this->origin instanceof ISessionMembershipOrigin:
                return true;
            default:
                return false;
        }
    }

    /**
     * @param string $postVar
     * @return string
     */
    protected function prop(string $postVar): string
    {
        return 'prop_' . $postVar;
    }

    /**
     * @param string $postVar
     * @return string
     */
    protected function conf(string $postVar): string
    {
        return 'config_' . $postVar;
    }
}
