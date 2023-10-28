<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *********************************************************************/

declare(strict_types=1);

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Jobs\RunSync;
use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginRepository;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Origin\OriginImplementationTemplateGenerator;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\User\ARUserOrigin;
use srag\Plugins\Hub2\Sync\Summary\OriginSyncSummaryFactory;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;
use srag\Plugins\Hub2\UI\OriginConfig\OriginsTableGUI;
use srag\Plugins\Hub2\UI\OriginFormFactory;

/**
 * Class ConfigOriginsGUI
 * @package      srag\Plugins\Hub2\UI\OriginConfig
 * @author       Stefan Wanzenried <sw@studer-raimann.ch>
 * @author       Fabian Schmid <fs@studer-raimann.ch>
 * @ilCtrl_calls hub2ConfigOriginsGUI: hub2DataGUI
 * @ilCtrl_calls hub2ConfigOriginsGUI: hub2LogsGUI
 */
class hub2ConfigOriginsGUI extends hub2MainGUI
{
    public const CMD_DELETE = 'delete';
    public const ORIGIN_ID = 'origin_id';
    public const SUBTAB_ORIGINS = 'subtab_origins';
    public const SUBTAB_DATA = 'subtab_data';
    public const CMD_RUN = 'run';
    public const CMD_RUN_FORCE_UPDATE = 'runForceUpdate';
    public const CMD_ADD_ORIGIN = 'addOrigin';
    public const CMD_EDIT_ORGIN = 'editOrigin';
    public const CMD_RUN_ORIGIN_SYNC = 'runOriginSync';
    public const CMD_RUN_ORIGIN_SYNC_FORCE_UPDATE = 'runOriginSyncForceUpdate';
    public const CMD_CONFIRM_DELETE = 'confirmDelete';
    public const CMD_CREATE_ORIGIN = 'createOrigin';
    public const CMD_SAVE_ORIGIN = 'saveOrigin';
    public const CMD_CANCEL = 'cancel';
    public const CMD_DEACTIVATE_ALL = 'deactivateAll';
    public const CMD_ACTIVATE_ALL = 'activateAll';
    /**
     * @var OriginSyncSummaryFactory
     */
    protected OriginSyncSummaryFactory $summaryFactory;
    /**
     * @var OriginFactory
     */
    protected OriginFactory $originFactory;
    /**
     * @var IOriginRepository
     */
    protected $originRepository;

    /**
     * ConfigOriginsGUI constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->originFactory = new OriginFactory();
        $this->originRepository = new OriginRepository();
        $this->summaryFactory = new OriginSyncSummaryFactory();
    }

    /**
     *
     */
    public function executeCommand()
    {
        $this->checkAccess();
        parent::executeCommand();
        // require_once "./Customizing/global/plugins/Services/Cron/CronHook/Hub2/sql/dbupdate.php";
        switch ($this->ctrl->getNextClass()) {
            case strtolower(hub2DataGUI::class):
                $this->ctrl->forwardCommand(new hub2DataGUI());
                break;
            case strtolower(hub2LogsGUI::class):
                $this->ctrl->forwardCommand(new hub2LogsGUI());
                break;
        }
    }

    /**
     *
     */
    protected function initTabs()
    {
        self::dic()->tabs()->addSubTab(
            self::SUBTAB_ORIGINS,
            ilHub2Plugin::getInstance()->txt(self::SUBTAB_ORIGINS),
            $this->ctrl
                ->getLinkTarget($this, self::CMD_INDEX)
        );

        self::dic()->tabs()->addSubTab(
            self::SUBTAB_DATA,
            ilHub2Plugin::getInstance()->txt(self::SUBTAB_DATA),
            $this->ctrl
                ->getLinkTargetByClass(hub2DataGUI::class, hub2MainGUI::CMD_INDEX)
        );

        self::dic()->tabs()->addSubTab(
            hub2LogsGUI::SUBTAB_LOGS,
            ilHub2Plugin::getInstance()->txt("logs", hub2LogsGUI::LANG_MODULE_LOGS),
            $this->ctrl
                                                                                  ->getLinkTargetByClass(
                                                                                      hub2LogsGUI::class,
                                                                                      hub2MainGUI::CMD_INDEX
                                                                                  )
        );

        self::dic()->tabs()->activateTab(self::TAB_ORIGINS);
        self::dic()->tabs()->activateSubTab(self::SUBTAB_ORIGINS);
    }

    /**
     *
     */
    protected function index()
    {
        self::dic()->toolbar()->setFormAction($this->ctrl->getFormAction($this));

        $button = ilSubmitButton::getInstance();
        $button->setCaption(ilHub2Plugin::getInstance()->txt('origin_table_button_add'), false);
        $button->setPrimary(true);
        $button->setCommand(self::CMD_ADD_ORIGIN);
        self::dic()->toolbar()->addButtonInstance($button);

        self::dic()->toolbar()->addSeparator();

        $button = ilSubmitButton::getInstance();
        $button->setCaption(ilHub2Plugin::getInstance()->txt('origin_table_button_run'), false);
        $button->setCommand(self::CMD_RUN);
        self::dic()->toolbar()->addButtonInstance($button);

        $button = ilSubmitButton::getInstance();
        $button->setCaption(ilHub2Plugin::getInstance()->txt('origin_table_button_run_force_update'), false);
        $button->setCommand(self::CMD_RUN_FORCE_UPDATE);
        self::dic()->toolbar()->addButtonInstance($button);

        $table = new OriginsTableGUI($this, self::CMD_INDEX, new OriginRepository());
        self::output()->output($table);
    }

    /**
     *
     */
    protected function cancel()
    {
        $this->index();
    }

    /**
     *
     */
    protected function addOrigin()
    {
        $form = new OriginConfigFormGUI($this, new OriginRepository(), new ARUserOrigin());
        self::output()->output($form);
    }

    /**
     *
     */
    protected function createOrigin()
    {
        $form = new OriginConfigFormGUI($this, new OriginRepository(), new ARUserOrigin());
        if ($form->checkInput()) {
            $origin = $this->originFactory->createByType($form->getInput('object_type'));
            $origin->setTitle($form->getInput('title'));
            $origin->setDescription($form->getInput('description'));
            $origin->store();
            ilUtil::sendSuccess(ilHub2Plugin::getInstance()->txt('msg_success_create_origin'), true);
            $this->ctrl->setParameter($this, self::ORIGIN_ID, $origin->getId());
            $this->ctrl->redirect($this, self::CMD_EDIT_ORGIN);
        }
        $form->setValuesByPost();
        self::output()->output($form);
    }

    /**
     *
     */
    protected function saveOrigin()
    {
        /** @var AROrigin $origin */
        $origin = $this->getOrigin((int) $_POST[self::ORIGIN_ID]);
        self::dic()->mainTemplate()->setTitle($origin->getTitle());
        $form = $this->getForm($origin);
        if ($form->checkInput()) {
            $origin->setTitle($form->getInput('title'));
            $origin->setSort($form->getInput(OriginConfigFormGUI::POST_VAR_SORT));
            $origin->setDescription($form->getInput('description'));
            $origin->setAdHoc($form->getInput(OriginConfigFormGUI::POST_VAR_ADHOC));
            $origin->setAdhocParentScope((bool) $form->getInput("adhoc_parent_scope"));
            $origin->setActive($form->getInput('active'));
            $origin->setImplementationClassName($form->getInput('implementation_class_name'));
            $origin->setImplementationNamespace($form->getInput('implementation_namespace'));
            // Get the config data as an array
            $configData = [];
            $propertyData = [];
            foreach ($form->getInputItemsRecursive() as $item) {
                if (strpos($item->getPostVar(), 'config_') === 0) {
                    $key = substr($item->getPostVar(), 7);
                    $configData[$key] = $form->getInput($item->getPostVar());
                } elseif (strpos($item->getPostVar(), 'prop_') === 0) {
                    $key = substr($item->getPostVar(), 5);
                    $propertyData[$key] = $form->getInput($item->getPostVar());
                }
            }
            $origin->config()->setData($configData);
            $origin->properties()->setData($propertyData);
            $origin->store();
            ilUtil::sendSuccess(ilHub2Plugin::getInstance()->txt('msg_origin_saved'), true);
            // Try to create the implementation class file automatically
            $generator = new OriginImplementationTemplateGenerator();
            try {
                $result = $generator->create($origin);
                if ($result) {
                    ilUtil::sendInfo(ilHub2Plugin::getInstance()
                                         ->translate(
                                             "msg_created_class_implementation_file",
                                             "",
                                             [$generator->getClassFilePath($origin)]
                                         ), true);
                }
            } catch (HubException $e) {
                ilUtil::sendInfo(ilHub2Plugin::getInstance()
                                     ->translate(
                                         "msg_created_class_implementation_file_failed",
                                         "",
                                         [$generator->getClassFilePath($origin)]
                                     ), true);
            }
            $this->ctrl->saveParameter($this, self::ORIGIN_ID);
            $this->ctrl->redirect($this, self::CMD_EDIT_ORGIN);
        }
        $form->setValuesByPost();
        self::output()->output($form);
    }

    /**
     *
     */
    protected function editOrigin()
    {
        $origin = $this->getOrigin((int) $_GET[self::ORIGIN_ID]);
        self::dic()->mainTemplate()->setTitle($origin->getTitle());
        $form = $this->getForm($origin);
        self::output()->output($form);
    }

    /**
     *
     */
    protected function activateAll()
    {
        foreach ($this->originRepository->all() as $repository) {
            $repository->setActive(true);
            $repository->store();
        }
        ilUtil::sendSuccess(ilHub2Plugin::getInstance()->txt('msg_origin_activated'), true);
        $this->ctrl->redirect($this);
    }

    /**
     *
     */
    protected function deactivateAll()
    {
        foreach ($this->originRepository->all() as $repository) {
            $repository->setActive(false);
            $repository->store();
        }
        ilUtil::sendSuccess(ilHub2Plugin::getInstance()->txt('msg_origin_deactivated'), true);
        $this->ctrl->redirect($this);
    }

    /**
     * @param IOrigin $origins
     * @param bool    $force_update
     */
    protected function execute(array $origins, bool $force_update = false)
    {
        $summary = $this->summaryFactory->web();

        (new RunSync($origins, $summary, $force_update))->run();

        ilUtil::sendInfo(nl2br($summary->getOutputAsString(), false), true);

        $this->ctrl->redirect($this);
    }

    /**
     * @param bool $force_update
     */
    protected function run(bool $force_update = false)/*: void*/
    {
        $this->execute($this->originFactory->getAllActive(), $force_update);
    }

    /**
     *
     */
    protected function runForceUpdate()/*: void*/
    {
        $this->run(true);
    }

    /**
     * @param bool $force_update
     */
    protected function runOriginSync(bool $force_update = false)/*: void*/
    {
        $origin = $this->getOrigin(intval(filter_input(INPUT_GET, self::ORIGIN_ID)));

        $this->execute([$origin], $force_update);
    }

    /**
     *
     */
    protected function runOriginSyncForceUpdate()/*: void*/
    {
        $this->runOriginSync(true);
    }

    /**
     *
     */
    protected function confirmDelete()
    {
        $f = new OriginFactory();
        $o = $f->getById(self::dic()->http()->request()->getQueryParams()[self::ORIGIN_ID]);

        $c = new ilConfirmationGUI();
        $c->setFormAction($this->ctrl->getFormAction($this));
        $c->addItem(self::ORIGIN_ID, (string) $o->getId(), $o->getTitle());
        $c->setConfirm(ilHub2Plugin::getInstance()->txt('confirm_delete_button'), self::CMD_DELETE);
        $c->setCancel(ilHub2Plugin::getInstance()->txt('cancel_delete_button'), self::CMD_INDEX);
        self::output()->output($c);
    }

    /**
     *
     */
    protected function delete()
    {
        $origin_id = intval(self::dic()->http()->request()->getParsedBody()[self::ORIGIN_ID]);

        $f = new OriginFactory();
        $f->delete($origin_id);

        $this->ctrl->redirect($this, self::CMD_INDEX);
    }

    /**
     * Check access based on plugin configuration.
     * Returns to personal desktop if a user does not have permission to administrate hub.
     */
    protected function checkAccess()
    {
        $roles = array_unique(array_merge(ArConfig::getField(ArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS), [2]));
        if (!self::dic()->rbacreview()->isAssignedToAtLeastOneGivenRole(self::dic()->user()->getId(), $roles)) {
            ilUtil::sendFailure(ilHub2Plugin::getInstance()->txt('permission_denied', "", [], false), true);
            $this->ctrl->redirectByClass(ilPersonalDesktopGUI::class);
        }
    }

    /**
     * @param AROrigin $origin
     * @return OriginConfigFormGUI
     */
    protected function getForm(AROrigin $origin): OriginConfigFormGUI
    {
        $formFactory = new OriginFormFactory();
        $formClass = $formFactory->getFormClassNameByOrigin($origin);
        return new $formClass($this, new OriginRepository(), $origin);
    }

    /**
     * @param int $id
     * @return AROrigin
     * @throws ilException
     */
    protected function getOrigin(int $id): AROrigin
    {
        /** @var AROrigin $origin */
        $origin = $this->originFactory->getById($id);
        if ($origin === null) {
            throw new ilException(sprintf("Origin with ID '%s' not found.", $id));
        }

        return $origin;
    }
}
