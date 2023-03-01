<?php

if(!defined('_PS_VERSION_'))
{
    exit;
}

class MyModule extends Module {

 
        public function __construct()
        {
            $this->displayName = $this->l('Mymodule');
            $this->name = 'mymodule';
            $this->description = $this->l("the best module ever");
            $this->tab = 'front_office_features';
            $this->version = '0.0.1';
            $this->author = 'laurent ndaw';
            $this->confirmUninstall = "do you really want to unistall the module ?";
            $this->ps_versions_compliancy = [
                'min' => '1.6',
                'max' => _PS_VERSION_,
            ];
            $this->bootstrap = true;
            parent::__construct();
    
        }



    // on mettra dans la méthode install
    // les variables de configuration
    // Crée un table si besoin
    // on peut ajouter des hooks
    public function install()
    {
        if(!parent::install() ||
        !Configuration::updateValue("KEY", time()) ||
        !$this->createtable() ||
        !$this->installtab('AdminMyModule','mes paramètres','AdminCatalog'))
        {
            return false;
        }
        return true;
    }


    public function uninstall()
    {
        if(!parent::uninstall() ||
        !Configuration::deleteByName('KEY') ||
        !$this->deletetable() ||
        !$this->uninstalltab())
        {
            return false;
        }

        return true;
    }

    // la méthode getContent permet d'afficher le formulaire de configuration
    // de la clé.
    public function getContent()
    {
         return $this->postProcess().$this->renderForm();   
    }

    // la méthode renderform permet de créer le formulaire
    public function renderForm()
    {
         $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings')
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('clé de configuration'),
                    'name' => 'KEY',
                    'size' => 20,
                    'required' => true
                ]
                ],
              'submit' => [
                'title' => $this->l('save'),
                'class' => 'btn btn-primary',
                'name' => 'saving'
              ]
         ];

         $helper = new HelperForm();
         $helper->module = $this;
         $helper->name_controller = $this->name;
         $helper->currentIndex =  $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

         $helper->token = Tools::getAdminTokenLite('AdminModules');
         $helper->fields_value['KEY'] = Configuration::get('KEY');
         return $helper->generateForm($fieldsForm);

    }

    // la methode postProcess permet de valider les données du formulaire
    public function postProcess()
    {
        // isSubmit permet de vérifier que le formulaire a bien été validé.
        if(Tools::isSubmit('saving'))
        {
            // la methode Tools::getValue récupère les données en GET et EN POST
            if(empty(Tools::getValue('KEY')))
            {
                return $this->displayError('La clé est vide');
            }
            else{
                Configuration::updateValue('KEY', Tools::getValue('KEY'));
                return $this->displayConfirmation('La clé a bien été modifiée');
            }
        }

    }

    public function createtable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'mymodule (
            id_parameter INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            parameter VARCHAR(255) NOT NULL)');
    }

    public function deletetable()
    {
        return Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.'mymodule');
    }

    public function installtab($className, $tabName, $tabParentName = false)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();

        foreach(Language::getLanguages(true) as $lang)
        {
            $tab->name[$lang['id_lang']] = $tabName;
        }

        if($tabParentName)
        {
            $tab->id_parent = Tab::getIdFromClassName($tabParentName);
        }else{
            $tab->id_parent = 0;
        }

        $tab->module = $this->name;

        return true;
    }

    public function uninstalltab()
    {
        $id_tab = Tab::getIdFromClassName('AdminMymodule');
        $tab = new tab($id_tab);
        return $tab->delete();
    }


    



}