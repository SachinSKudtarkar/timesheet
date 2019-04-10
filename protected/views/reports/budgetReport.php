<?php
$this->breadcrumbs = array(
    'Reports ' => array('timesheetreports'),
);
?>
<h1 style="margin-bottom: 5px;">Budget Report</h1>

<div class="span12" style="margin-left:0px;">
<table class="table table-bordered responsive" id="projectreports">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Project Name</th>
            <th><?php echo $levelArray[1]['name']; ?><hr / >$<?php echo $levelArray[1]['budget']; ?></th>
            <th><?php echo $levelArray[2]['name']; ?><hr / >$<?php echo $levelArray[2]['budget']; ?></th>
            <th><?php echo $levelArray[3]['name']; ?><hr / >$<?php echo $levelArray[3]['budget']; ?></th>
            <th><?php echo $levelArray[4]['name']; ?><hr / >$<?php echo $levelArray[4]['budget']; ?></th>
            <th>Level (Undefined)</th>
            <th>Estimated Hours</th>
            <th>Utilized Hours</th>
            <th>Estimated Budget</th>
            <th>Utilized Budget</th>
        </tr>
    </thead>

    <tbody>
            <?php $sr=1; ?>
            <?php foreach($dataArr as $dataRow){ ?>
            <?php $est = $used = []; ?>
            <tr>
                <td><?php echo $sr++; ?></td>
                <td><?php echo $dataRow['project_name']; ?></td>
                <td>
                    <?php
                        echo $est[1] = isset($dataRow['hours'][1]['est']) ? $dataRow['hours'][1]['est'] : 0;
                        $estBudget[1] = $est[1] * $levelArray[1]['budget']; ?>
                    <hr />
                    <?php echo $used[1] = isset($dataRow['hours'][1]['used']) ? $dataRow['hours'][1]['used'] : 0;
                        $usedBudget[1] = $used[1] * $levelArray[1]['budget']; ?>
                </td>
                <td>
                    <?php echo $est[2] = isset($dataRow['hours'][2]['est']) ? $dataRow['hours'][2]['est'] : 0;
                        $estBudget[2] = $est[2] * $levelArray[2]['budget']; ?>
                    <hr />
                    <?php echo $used[2] = isset($dataRow['hours'][2]['used']) ? $dataRow['hours'][2]['used'] : 0;
                        $usedBudget[2] = $used[2] * $levelArray[2]['budget']; ?>
                </td>
                <td>
                    <?php echo $est[3] = isset($dataRow['hours'][3]['est']) ? $dataRow['hours'][3]['est'] : 0;
                        $estBudget[3] = $est[3] * $levelArray[3]['budget']; ?>
                    <hr />
                    <?php echo $used[3] = isset($dataRow['hours'][3]['used']) ? $dataRow['hours'][3]['used'] : 0;
                        $usedBudget[3] = $used[3] * $levelArray[3]['budget']; ?>
                </td>
                <td>
                    <?php echo $est[4] = isset($dataRow['hours'][4]['est']) ? $dataRow['hours'][4]['est'] : 0;
                        $estBudget[4] = $est[4] * $levelArray[4]['budget']; ?>
                    <hr />
                    <?php echo $used[4] = isset($dataRow['hours'][4]['used']) ? $dataRow['hours'][4]['used'] : 0;
                        $usedBudget[4] = $used[4] * $levelArray[4]['budget']; ?>
                </td>
                <td><?php echo $used[0] = isset($dataRow['hours'][0]['used']) ? $dataRow['hours'][0]['used'] : 0; ?></td>
                <td><?php echo array_sum($est); ?></td>
                <td><?php echo array_sum($used); ?></td>
                <td><?php echo array_sum($estBudget); ?></td>
                <td><?php echo array_sum($usedBudget); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
