<section>
    <div class="lead text-success"><?=lang('email:title')?></div>
    <table class="table">
        <thead>
            <tr>
                <th>Oganización</th>
                <th>Path</th>
                <th class="text-center">Usuarios</th>
                <th width="20%"></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($orgs  as $org): ?>
            <tr>
                <td><?=$org->name?></td>
                <td><?=$org->org_path?></td>
                <td class="text-center"><?php echo $org->users? count($org->users):'0'?></td>
                <td class="text-center">
                    
                    <a href="<?=base_url('admin/emails/organizaciones/edit/'.$org->id)?>">Editar</a>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</section>