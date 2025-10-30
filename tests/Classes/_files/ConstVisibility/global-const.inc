<?php

const GLOBAL_CONST = 'global';

define('ANOTHER_GLOBAL', 'value');
<?php

class TestClass
{

    public const PUBLIC_CONST = 'public';
    protected const PROTECTED_CONST = 'protected';
    private const PRIVATE_CONST = 'private';

}

