<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This is the package.xml generator for ZangApi
 *
 * PHP version 5
 *
 * LICENSE:
 *
 * Copyright 2020 Avaya Cloud Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category  Zang
 * @package   ZangApi
 * @author    Nevio Vesic <nevio@zang.io>
 * @copyright 2020 Avaya Cloud Inc
 * @license   http://creativecommons.org/licenses/MIT/
 */

error_reporting(E_ALL & ~E_DEPRECATED);
require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$api_version     = '1.0.0';
$api_state       = 'stable';

$release_version = '1.0.1';
$release_state   = 'stable';
$release_notes   = 'Adding PEAR package improvements';

$release_version = '1.0.2';
$release_state   = 'stable';
$release_notes   = 'Adding PEAR package improvements';

$description = <<<DESC
Zang PHP wrapper is an open source tool built for easy access to the zang.io API infrastructure. Zang is a powerful cloud communications API built to enable your apps to send and receive SMS messages and phone calls — all while controlling the call flow. Some features are conferencing, phone calls, text-to-speech, recordings, transcriptions and much more.
DESC;

$package = new PEAR_PackageFileManager2();

$package->setOptions(
    array(
        'filelistgenerator'       => 'file',
        'simpleoutput'            => true,
        'baseinstalldir'          => '/',
        'packagedirectory'        => './',
        'dir_roles'               => array(
            'schemas'  => 'data',
            'library'  => 'php',
            'library/ZangApi' => 'php'
            
        ),
        'ignore'                  => array(
            'examples/*',
	        'logs/*',
            'package.php',
            'package.xml~',
            'package.php~',
            '*.tgz',
            '*.md',
            'scratch/*',
            'vendor/*',
            'composer.*',
            'coverage/*',
            'docs/*',
            'travis_install.bash',
            '.travis.yml',
        )
    )
);

$package->setPackage('ZangApi');
$package->setSummary('PHP helper library for Zang');
$package->setDescription($description);
$package->setChannel('pear.zang.io');
$package->setPackageType('php');
$package->setLicense(
    'MIT License',
    'http://creativecommons.org/licenses/MIT/'
);

$package->setNotes($release_notes);
$package->setReleaseVersion($release_version);
$package->setReleaseStability($release_state);
$package->setAPIVersion($api_version);
$package->setAPIStability($api_state);

$package->addMaintainer(
    'lead',
    '0x19',
    'Nevio Vesic',
    'nevio@zang.io'
);


$package->setPhpDep('5.2.1');

#$package->addPackageDepWithChannel('optional');

$package->setPearInstallerDep('1.9.3');
$package->generateContents();
$package->addRelease();

if (isset($_GET['make'])
    || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')
) {
    $package->writePackageFile();
} else {
    $package->debugPackageFile();
}

?>

