# zf2-modal-module
ZF2 Simple modal module 

Created for [Testapic](http://www.testapic.com "Testez - Optimisez - Rentabilisez")

Introduction
------------
This module is a Zend Framework 2 module to generate some simple and custom modals.

![Alt text](/tree.png?raw=true "Tree")

Configuration
-------------
1. ZF2 Version 2.3.4
2. Semantic UI

Installation
-----------
You have to put Semantic UI at "/public/css/" and don't forget to set your css framwork link.

If you want to include a css framwork
`` $headlink->prependStylesheet('/css/dist/semantic.min.css'); ``

Usage
-----
1. Controller
The modal variable contains the data to generate modal view.

Override Mode
Mandatory : confirmAction
```
    $modal = array(
        "id" => "Something",
        'url_redir' => $this->getRequest()->getUri()->getPath(),
	'url_confirm' => 'PATH_OF_CONFIRM_VIEW',
        'title' => "Title",
        'flash' => array("Sentence 1"),
        'content' => "Display something",
        'btn' => array("confirm" => "Ok", "noconfirm" => "Fermer"),
    );
```

Custom Mode
Mandatory : infoAction, confirmAction
```
    $modal = array(
        "id" => "Something",
        'url_redir' => $this->getRequest()->getUri()->getPath(),
        'url_info' => 'PATH_OF_INFO_VIEW',
        'url_confirm' => 'PATH_OF_CONFIRM_VIEW',
    );
```

View
```
    <a href="#" class="smodal" data-modal='<?= $this->modallink(array("id" => 1), $this->modal) ?>'>
        Display modal
    </a>
```

2. infoAction
If 'url_info' isn't set, '/ui/modal/info' will be called by defaut.

* Generate a modal
```
    public function infoAction() {
        $modal = new ModalController($this, 'PATH_OF_INFO_VIEW');
        $data = array(); // Data ready to be used in infoAction variable named $page
        $modal->setTitle("Title");
        $modal->setData($data);
        $modal->setButton($modal->data->btn);
        return $modal->render();
    }
```

* View of action infoAction (info.phtml)
```
<?php
  $page = (object) $this->layout()->child_page;
?>
<?= $this->translate("Souhaîtez-vous vraiment effectuer cette action sur l'élement ?"); ?>
```

3. confirmAction
If 'url_confirm' isn't set, '/ui/modal/confirm' will be called by default.

* Generate a modal
```
    public function confirmAction() {
        $modal = new ModalController($this, 'PATH_OF_CONFIRM_VIEW');
        $modal->setFlashMessenger("L'action a bien été éffectuée");
        // Add some queries
        $modal->setTitle("Title");
        return $modal->render();
    }
```

* View of action infoAction (info.phtml)
```
<?php
  $page = (object) $this->layout()->child_page;
?>
<div class="ui grid">
    <div class="column">
        <div class="ui red large message">
            <?php echo $this->translate($page->string); ?>
        </div>
    </div>
</div>
```