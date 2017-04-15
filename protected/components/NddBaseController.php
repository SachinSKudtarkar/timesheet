<?php

/**
 * Description of NddBaseController
 *
 * @author Pratik Gotmare <pratikgotmare@ocatalog.com>
 */
class NddBaseController extends BaseController {

    public function multipleInsert($model, $importData, $transactionSupport = true) {
        if (!empty($importData)) {
            $batches = array();
            $batches = array_chunk($importData, 200);
            $error = false;
            $success = false;
            $errorMessage = null;
            foreach ($batches as $batch) {
                if ($transactionSupport)
                    $transaction = $model->getDbConnection()->beginTransaction();
                try {
                    $query = $model->commandBuilder->createMultipleInsertCommand($model->tableName(), $batch);
                    $query->execute();
                    if ($transactionSupport)
                        $transaction->commit();
                    $success = true;
                } catch (Exception $ex) {
                    if ($transactionSupport)
                        $transaction->rollback();
                    $error = true;
                    $errorMessage = $ex->getMessage();
                }
                usleep(1000);
            }
            if ($error && $success) {
                Yii::app()->user->setFlash('error', "Data partially imported.");
            } else if ($error) {
                Yii::app()->user->setFlash('error', "Import Failed - " . $errorMessage);
            } else if ($success) {
                Yii::app()->user->setFlash('success', "Data imported successfully.");
            }
        } else {
            Yii::app()->user->setFlash('error', "No records found.");
        }
    }

    public function multipleInsertAG1($model, $importData, $is_duplicate_record = false) {
        if (!empty($importData)) {
            $batches = array();
            $batches = array_chunk($importData, 200);
            /* echo '<pre>';
              print_r($importData);
              echo "====================================================="."<br>";
              print_r($batches);
              die; */
            $error = false;
            $success = false;
            $errorMessage = null;
            $i = 1;
            foreach ($batches as $batch) {

                $hostNameEntries = array();
                $transaction = $model->getDbConnection()->beginTransaction();
                try {
                    $query = $model->commandBuilder->createMultipleInsertCommand($model->tableName(), $batch);
                    $query->execute();

                    /* print_r($batch);
                      echo $sapid = $batch['sapid'];
                      die; */
                    $hostNameModel = new NddHostName();
                    foreach ($batch as $row) {
                        $hostname = $row['hostname'];
                        if (!empty($hostname)) {
                            $facid = $row['facid'];
                            $sapid = $row['sapid'];
                            $gne_id = $row['neid'];
                            $file_name = $row['file_name'];
                            $created_at = $row['created_at'];
                            $modified_at = $row['modified_at'];
                            if (!NddHostName::model()->exists("sapid = '$sapid' AND facid = '$facid'") && !NddHostName::model()->exists("host_name = '$hostname'")) {
                                $hostNameEntries[] = array(
                                    'host_name' => $row['hostname'],
                                    'facid' => $row['facid'],
                                    'sapid' => $row['sapid'],
                                    'modified_sapid' => Yii::app()->commUtility->modifySAPID($row['sapid']),
                                    'gne_id' => $row['neid'],
                                    'created_at' => $row['created_at'],
                                    'modified_at' => $row['modified_at']);
                            }
                            //Import Failed - Property "NddHostName.hostname" is not defined.
                            /*


                              if (!$hostNameModel->exists("sapid = '$sapid' AND facid = '$facid'") && !$hostNameModel->exists("host_name = '$hostname'")) {

                              var_dump($hostNameModel->exists("sapid = '$sapid' AND facid = '$facid'"));
                              var_dump($hostNameModel->exists("host_name = '$hostname'"));
                              echo $sapid."<br>";
                              echo $facid."<br>";
                              echo $hostname."<br>";

                              echo "<br>";
                              $hostNameModel->host_name = $hostname;
                              $hostNameModel->facid = $facid;
                              $hostNameModel->sapid = $sapid;
                              $hostNameModel->modified_sapid = Yii::app()->commUtility->modifySAPID($sapid);
                              $hostNameModel->gne_id = $sapid;
                              $hostNameModel->comment = '';
                              $hostNameModel->status = '';
                              $hostNameModel->file_name = $file_name;
                              $hostNameModel->created_at = $created_at;
                              $hostNameModel->modified_at = $modified_at;
                              $hostNameModel->save(false); */
                        }
                    }

                    if (!empty($hostNameEntries)) {
                        $query = $hostNameModel->commandBuilder->createMultipleInsertCommand($hostNameModel->tableName(), $hostNameEntries);
                        $query->execute();
                    }
                    $transaction->commit();
                    /*
                      print_r($batch);
                      die; */
                    $success = true;
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $error = true;
                    $errorMessage = $ex->getMessage();
                }
                usleep(1000);
                $i++;
            }
            if ($error && $success) {
                Yii::app()->user->setFlash('error', "Data partially imported.");
            } else if ($error) {
                Yii::app()->user->setFlash('error', "Import Failed - " . $errorMessage);
            } else if ($success) {
                Yii::app()->user->setFlash('success', "Data imported successfully.");
            }
        } else {
            Yii::app()->user->setFlash('error', "No records found.");
        }
    }

    /**
     * Export data excel
     * 
     * @param array $columns
     * @param array $data
     * @param string $filename
     */
    public function exportDataExcel($columns, $data, $filename = null) {
        if (!empty($data)) {
            $exportData = array();
            foreach ($data as $record) {
                $row = array();
                foreach ($columns as $key => $label) {
                    $value = "";
                    if (isset($record[$key])) {
                        $value = $record[$key];
                    }
                    $row[$key] = $value;
                }
                if (!empty($row))
                    $exportData[] = $row;
            }
            if (!empty($exportData)) {
                CommonUtility::generateExcel($columns, $exportData);
            }
        }
    }

    public function updateRequest($request) {
        if (!empty($request['NddTempInput'])) {
            $updateSapidsFields = array('enode_b_sapid', 'microwave_takeoff_point', 'east_ag1_sapid', 'west_ag1_sapids');
            foreach ($request['NddTempInput'] as $row) {
                if (!empty($row['temp_input_id'])) {
                    $obj = new NddTempInput();
                    $obj->setAttributes($row, FALSE);
                    $obj->setModifiedSapids();
                    $tempAttributes = $obj->getAttributes();                    
                    foreach ($updateSapidsFields as $field) {
                        if (isset($row[$field]) && isset($tempAttributes[$field])) {
                            $row[$field] = $tempAttributes[$field];
                        }
                    }                    
                    NddTempInput::model()->updateByPk($row['temp_input_id'], $row);                    
                }
            }
        }
    }

}

?>
