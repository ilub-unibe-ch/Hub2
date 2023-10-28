<?php
declare(strict_types=1);

require_once __DIR__ . "/../AbstractHub2Tests.php";

/**
 * Class MetadataTest
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 */
class MetadataTest extends AbstractHub2Tests
{

    protected function setUp()
    {

    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function test_one_metadata_dto()
    {

    }
}
