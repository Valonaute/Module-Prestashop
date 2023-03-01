<?php
require_once(_PS_MODULE_DIR_.'mymodule/classes/MyModuleClass.php');
class AdminMyModuleController extends ModuleAdminController {

    public function __construct()
    {
        $this->table = MyModuleClass::$definition['table'];
        $this->className = MyModuleClass::class;
        $this->module = Module::getInstanceByName('mymodule');
        $this->identifier =  MyModuleClass::$definition['primary'];
        $this->_orderBy = 'id_parameter';
        $this->bootstrap = true;
        parent::__construct();

        $this->fields_list = array(
            'id_parameter' => array(
                'title' => 'ID',
                'search' => false
            ),
            'parameter' => array(
                'title'=> 'parameter',
                'search' => false
            )
        );

        $this->addRowAction('edit');
        $this->addRowAction('delete');

    }


    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => 'ma configuration',
                'icon' => 'icon-cog'
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => 'parameter',
                    'name' => 'parameter',
                    'required' => true
                ]

            ],
            'submit' => [
                'title' => 'valider le formulaire',
                'class' => 'btn btn-primary'
            ]
            ];

            return parent::renderForm();
    }
}