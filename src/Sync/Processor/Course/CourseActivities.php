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

namespace srag\Plugins\Hub2\Sync\Processor\Course;

use ilDB;
use ilDBInterface;
use ilObjCourse;
use ilObject2;

/**
 * Class CourseActivities
 * @package srag\Plugins\Hub2\Sync\Processor\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseActivities implements ICourseActivities
{
    /**
     * @var ilDB
     */
    protected $db;

    /**
     * @param ilDBInterface $db
     */
    public function __construct(ilDBInterface $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritdoc
     */
    public function hasActivities(ilObjCourse $ilObjCourse): bool
    {
        $sql = "SELECT 
				    wre.*, dat.*, rbac_ua.*
				FROM
				    catch_write_events AS wre
				        JOIN
				    obj_members AS mem ON mem.obj_id = wre.obj_id AND mem.usr_id = wre.usr_id	        
				        JOIN object_reference AS ref ON ref.obj_id = wre.obj_id				        
				        JOIN object_data AS dat ON dat.type = 'role' AND dat.title = CONCAT('il_crs_member_', ref.ref_id)				        
				        JOIN rbac_ua ON rbac_ua.rol_id = dat.obj_id AND rbac_ua.usr_id = wre.usr_id				        
				WHERE
				    wre.obj_id = " . $this->db->quote(ilObject2::_lookupObjId($ilObjCourse->getRefId()), 'integer');
        $query = $this->db->query($sql);

        return ($this->db->numRows($query) > 0);
    }
}
