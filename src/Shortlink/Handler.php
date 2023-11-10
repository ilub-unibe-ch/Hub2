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

namespace srag\Plugins\Hub2\Shortlink;

use ilContext;
use ilDBInterface;
use ilHub2Plugin;
use ilInitialisation;

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\ShortlinkException;

/**
 * Class Handler
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Handler
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    public const PLUGIN_BASE = "Customizing/global/plugins/Services/Cron/CronHook/Hub2/";
    protected \ilCtrlInterface $ctrl;
    protected \ILIAS\DI\UIServices $ui;
    protected \ilObjUser $user;
    protected ilDBInterface $database;
    /**
     * @var bool
     */
    protected bool $init = false;
    /**
     * @var ObjectLinkFactory
     */
    protected ObjectLinkFactory $object_link_factory;
    /**
     * @var string
     */
    protected string $ext_id = '';

    /**
     * Handler constructor
     * @param string $ext_id
     */
    public function __construct(string $ext_id)
    {
        global $DIC;

        $this->init = false;
        $this->ext_id = $ext_id;
        $this->database = $DIC->database();
        $this->ctrl = $DIC->ctrl();
        $this->ui = $DIC->ui();
        $this->user = $DIC->user();
    }

    /**
     *
     */
    public function storeQuery()
    {
        $return = setcookie('xhub_query', $this->ext_id, time() + 10);
    }

    /**
     * @throws ShortlinkException
     */
    public function process()
    {
        if (!$this->init || !$this->database instanceof ilDBInterface) {
            throw new ShortlinkException("ILIAS not initialized, aborting...");
        }

        $object_link_factory = new ObjectLinkFactory();

        $link = $object_link_factory->findByExtId($this->ext_id);

        if (!$link->doesObjectExist()) {
            $this->sendMessage(ArConfig::getField(ArConfig::KEY_SHORTLINK_OBJECT_NOT_FOUND));
            $this->doRedirect($link->getNonExistingLink());
        }

        if (!$link->isAccessGranted()) {
            $this->sendMessage(ArConfig::getField(ArConfig::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE));
            $this->doRedirect($link->getAccessDeniedLink());
        }
        $this->sendMessage(ArConfig::getField(ArConfig::KEY_SHORTLINK_SUCCESS));
        $this->doRedirect($link->getAccessGrantedExternalLink());
    }

    /**
     * @param string $link
     */
    protected function doRedirect(string $link)
    {
        $link = $this->sanitizeLink($link);
        $this->ctrl->redirectToURL($link);
    }

    /**
     * @param string $message
     */
    protected function sendMessage(string $message)
    {
        if ($message !== '') {
            $this->ui->mainTemplate()->setOnScreenMessage('info', $message, true);
        }
    }

    /**
     *
     */
    public function tryILIASInit()
    {
        $this->prepareILIASInit();

        require_once("Services/Init/classes/class.ilInitialisation.php");
        ilInitialisation::initILIAS();

        $this->init = true;
    }

    /**
     *
     */
    public function tryILIASInitPublic()
    {
        $this->prepareILIASInit();

        require_once 'Services/Context/classes/class.ilContext.php';
        ilContext::init(ilContext::CONTEXT_WAC);
        require_once "Services/Init/classes/class.ilInitialisation.php";
        ilInitialisation::initILIAS();
        $ilAuthSession = self::dic()->authSession();
        $ilAuthSession->init();
        $ilAuthSession->regenerateId();
        $a_id = ANONYMOUS_USER_ID;
        $ilAuthSession->setUserId($a_id);
        $ilAuthSession->setAuthenticated(false, $a_id);
        $this->user->setId($a_id);

        $this->init = true;
    }

    /**
     * @param string $link
     * @return mixed|string
     */
    protected function sanitizeLink(string $link)
    {
        $link = str_replace(self::PLUGIN_BASE, "", $link);
        $link = ltrim($link, "/");
        return "/{$link}";
    }

    /**
     *
     */
    protected function prepareILIASInit()
    {
        $GLOBALS['COOKIE_PATH'] = '/';
        $_GET["client_id"] = $_COOKIE['ilClientId'];
    }
}
