<?php
return array(
    'audit' => array(
        'datetime.format' => 'r',

        'tableNamePrefix' => '',
        'tableNameSuffix' => '_audit',
        'revisionTableName' => 'Revision',

        'entities' => array(
            'Application\Entity\Type',
            'ZfcUserDoctrineORM\Entity\User',
        ),
    ),
);

