<?php
// views/components/table_shell.php
?>
<div class="bg-white border border-navy/10 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-navy/5 border-b border-navy/10 text-left text-navy/70 font-semibold">
                    <?php foreach ($columns as $col): ?>
                        <th class="px-5 py-3"><?= e($col) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-navy/5">
                <?= $body ?>
            </tbody>
        </table>
    </div>
</div>
