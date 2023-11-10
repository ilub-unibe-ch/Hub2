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

namespace srag\Plugins\Hub2\Origin;

use ActiveRecord;
use ilHub2Plugin;

use srag\Plugins\Hub2\UI\Data\DataTableGUI;

/**
 * Class OriginFactory
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginFactory implements IOriginFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    protected \ilDBInterface $database;

    /**
     *
     */
    public function __construct()
    {
        global $DIC;

        $this->database = $DIC->database();
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): ?IOrigin
    {
        $sql = 'SELECT object_type FROM ' . AROrigin::TABLE_NAME . ' WHERE id = %s';
        $set = $this->database->queryF($sql, ['integer'], [$id]);
        $type = $this->database->fetchObject($set)->object_type;
        if (!$type) {
            //throw new HubException("Can not get type of origin id (probably deleted): ".$id);
            return null;
        }
        $class = $this->getClass($type);

        return $class::find($id);
    }

    /**
     * @inheritdoc
     */
    public function createByType(string $type): IOrigin
    {
        $class = $this->getClass($type);

        return new $class();
    }

    /**
     * @inheritdoc
     */
    public function getAllActive(): array
    {
        $sql = 'SELECT id FROM ' . AROrigin::TABLE_NAME . ' WHERE active = %s ORDER BY sort';
        $set = $this->database->queryF($sql, ['integer'], [1]);
        $origins = [];
        while ($data = $this->database->fetchObject($set)) {
            $origins[] = $this->getById((int)$data->id);
        }

        return $origins;
    }

    /**
     * @inheritdoc
     */
    public function getAll(): array
    {
        $origins = [];

        $sql = 'SELECT id FROM ' . AROrigin::TABLE_NAME . ' ORDER BY sort';
        $set = $this->database->query($sql);
        while ($data = $this->database->fetchObject($set)) {
            $origins[] = $this->getById((int)$data->id);
        }

        return $origins;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getClass(string $type): string
    {
        $ucfirst = ucfirst($type);
        return "srag\\Plugins\\Hub2\\Origin\\{$ucfirst}\\AR{$ucfirst}Origin";
    }

    /**
     * @param int $origin_id
     */
    public function delete(int $origin_id)/*: void*/
    {
        /**
         * @var ActiveRecord $object
         */

        foreach (DataTableGUI::$classes as $class) {
            foreach ($class::where(["origin_id" => $origin_id])->get() as $object) {
                $object->delete();
            }
        }

        $object = $this->getById($origin_id);
        $object->delete();
    }
}
