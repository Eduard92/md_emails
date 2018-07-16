<section>
    <div class="lead text-success"><?=lang('email:title')?></div>
    <table class="table">
        <thead>
            <tr>
                <th>Oganización</th>
                <th>Path</th>
                <th class="text-center">Usuarios</th>
                <th width="10%"></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($orgs  as $org): ?>
            <tr>
                <td><?=$org->name?></td>
                <td><?=$org->org_path?></td>
                <td class="text-center"><?php echo $org->users? count($org->users):'0'?></td>
                <td><a href="<?=base_url('admin/emails/organizaciones/add/'.$org->id)?>">Asignar</a>|
                    <a href="<?=base_url('admin/emails/organizaciones/config/'.$org->id)?>">Configuracion</a</td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</section>