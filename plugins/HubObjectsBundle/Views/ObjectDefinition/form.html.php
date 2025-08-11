<?php
$view->extend('MauticCoreBundle:Default:content.html.php');

$view['slots']->set('headerTitle', $item->getName() ?: $view['translator']->trans('mautic.hubobjects.definition.new'));

$view['form']->setTheme($form, 'HubObjectsBundle:FormTheme');

echo $view['form']->start($form);
?>

<div class="row">
    <div class="col-md-6">
        <?php
        echo $view['form']->row($form['name']);
        echo $view['form']->row($form['pluralName']);
        echo $view['form']->row($form['slug']);
        ?>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $view['translator']->trans('mautic.hubobjects.field.plural'); ?></h4>
            </div>
            <div class="panel-body">
                <ul id="field-list" class="list-group" data-prototype="<?php echo $view['form']->widget($form['fields']->getPrototype()); ?>">
                    <?php foreach ($form['fields'] as $fieldForm) : ?>
                        <li class="list-group-item">
                            <?php echo $view['form']->row($fieldForm); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" id="add-field" class="btn btn-primary"><?php echo $view['translator']->trans('mautic.hubobjects.field.add'); ?></button>
            </div>
        </div>
    </div>
</div>

<?php
echo $view['form']->end($form);
?>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var addFieldButton = document.getElementById('add-field');
    var fieldList = document.getElementById('field-list');
    var fieldCounter = fieldList.children.length;

    addFieldButton.addEventListener('click', function() {
        var prototype = fieldList.getAttribute('data-prototype');
        var newForm = prototype.replace(/__name__/g, fieldCounter);
        fieldCounter++;

        var li = document.createElement('li');
        li.className = 'list-group-item';
        li.innerHTML = newForm;

        // Add a remove button
        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-danger btn-sm';
        removeButton.innerText = 'Remove';
        removeButton.addEventListener('click', function() {
            li.remove();
        });
        li.appendChild(removeButton);

        fieldList.appendChild(li);
    });

    // Add remove buttons to existing fields
    Array.from(fieldList.children).forEach(function(li) {
        if (li.querySelector('.btn-danger') === null) {
            var removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-danger btn-sm';
            removeButton.innerText = 'Remove';
            removeButton.addEventListener('click', function() {
                li.remove();
            });
            li.appendChild(removeButton);
        }
    });
});
</script>
