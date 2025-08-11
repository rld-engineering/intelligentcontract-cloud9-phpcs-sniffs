<?php

class test
{

    private function foo()
    {
        $sectionsForForm = array_merge(
            $sectionsForForm,
            $section->settingsSectionForForm($request, $settingFields, $settingsName, $settingsFieldNames));
    }

}
