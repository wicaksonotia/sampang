<?php
$baseUrl = Yii::app()->request->baseUrl;
$baseJs = Yii::app()->appComponent->urlJs();
$baseCss = Yii::app()->appComponent->urlCss();
$path = $this->module->assetsUrl;
$cs = Yii::app()->clientScript;
$cs->registerScriptFile($path . '/js/retribusi.js', CClientScript::POS_END);
$cs->registerCssFile($baseUrl . '/css/bootstrap-select.css');
$cs->registerScriptFile($baseUrl . '/js/bootstrap-select.js', CClientScript::POS_END);
$cs->registerCssFile($baseCss . '/jquery.fileuploader.css');
$cs->registerScriptFile($baseJs . '/jquery.fileuploader.retribusi.js', CClientScript::POS_END);
$cs->registerScriptFile($baseJs . '/jquery.fileuploader.min.js', CClientScript::POS_END);
?>
<script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
<style>
    .input-group-btn select {
        border-color: #ccc;
        margin-top: 0px;
        margin-bottom: 0px;
        padding-top: 7px;
        padding-bottom: 6px;
    }

    .fileuploader {
        padding: 0px !important;
        margin: 0px !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Form Retribusi</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal', 'id' => 'formPendaftaran', 'enctype' => 'multipart/form-data')); ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="FORM_JENIS_PENGUJIAN" class="col-xs-3 control-label">Jenis Pengujian</label>
                        <div class="col-xs-9">
                            <?php
                            $type_list = CHtml::listData($tbl_uji, 'id_uji', 'nm_uji');
                            echo CHtml::dropDownList('FORM[JENIS_PENGUJIAN]', '', $type_list, array('class' => 'form-control col-xs-9', 'onchange' => 'selectJenisUji()', 'options' => array('1' => array('selected' => true))));
                            ?>
                        </div>
                    </div>
                    <div class="form-group" id="div_kartu_hilang" style="display: none">
                        <label for="FORM_KARTU_HILANG" class="col-xs-3 control-label">No. Surat Kehilangan</label>
                        <div class="col-xs-9">
                            <?php echo CHtml::textField('FORM[KARTU_HILANG]', '', array('class' => 'form-control text-besar', 'placeholder' => 'No. Surat Kehilangan')); ?>
                        </div>
                    </div>
                    <div class="form-group" id="div_wilayah_asal" style="display: none">
                        <label for="FORM_WILAYAH_ASAL" class="col-xs-3 control-label">Wilayah Asal</label>
                        <div class="col-xs-9">
                            <?php
                            $criteria_wilayah = new CDbCriteria();
                            // $criteria_wilayah->addNotInCondition('idx', array(12, 30, 145, 148, 149, 151));
                            $criteria_wilayah->order = 'area_name asc';
                            $tbl_wilayah = MasterArea::model()->findAll($criteria_wilayah);
                            $type_wilayah = CHtml::listData($tbl_wilayah, 'area_code', 'area_name');
                            echo CHtml::dropDownList('FORM[WILAYAH_ASAL]', '', $type_wilayah, array('class' => 'form-control selectpicker show-tick wajib-isi', 'data-live-search' => 'true', 'data-size' => '5', 'empty' => ''));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_NO_STUK" class="col-xs-3 control-label">No. Uji</label>
                        <div class="col-xs-9">
                            <div class="input-group">
                                <?php
                                $userlogin = Yii::app()->session['username'];
                                echo CHtml::hiddenField('FORM[USER_LOGIN]', $userlogin, array('class' => 'form-control text-besar'));
                                echo CHtml::textField('FORM[NO_STUK]', '', array('class' => 'form-control text-besar', 'placeholder' => 'No. Uji'));
                                echo CHtml::hiddenField('FORM[ID_KENDARAAN]', 0, array('class' => 'form-control'));
                                ?>
                                <div class="input-group-addon">
                                    <a href="javascript:void(0)" onclick="prosesSearchDetailSb('<?php echo $this->createUrl('Default/DetailNoSb'); ?>', 'sb')"><i class="glyphicon glyphicon-search"></i></a>
                                </div>
                            </div>
                            <span id="loading_stuk" style="display: none"><img src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" class="loader"></span>
                            <small id="tidak_ada" style="display: none; color: red;">Data tidak ditemukan</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_NO_KENDARAAN" class="col-xs-3 control-label">No. Kendaraan</label>
                        <div class="col-xs-9">
                            <div class="input-group">
                                <?php
                                echo CHtml::textField('FORM[NO_KENDARAAN]', '', array('class' => 'form-control text-besar', 'placeholder' => 'No. Kendaraan'));
                                ?>
                                <div class="input-group-addon">
                                    <a href="javascript:void(0)" onclick="prosesSearchDetailSb('<?php echo $this->createUrl('Default/DetailNoSb'); ?>', 'no_kend')"><i class="glyphicon glyphicon-search"></i></a>
                                </div>
                            </div>
                            <span id="loading_stuk" style="display: none"><img src="<?php echo Yii::app()->baseUrl; ?>/images/loading.gif" class="loader"></span>
                            <small id="tidak_ada" style="display: none; color: red;">Data tidak ditemukan</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_NO_MESIN" class="col-xs-3 control-label">No. Mesin</label>
                        <div class="col-xs-9">
                            <div class="input-group">
                                <?php echo CHtml::textField('FORM[NO_MESIN]', '', array('class' => 'form-control text-besar', 'placeholder' => 'No. Mesin')); ?>
                                <div class="input-group-addon">
                                    <a href="javascript:void(0)" onclick="prosesSearchDetailSb('<?php echo $this->createUrl('Default/DetailNoSb'); ?>', 'no_mesin')"><i class="glyphicon glyphicon-search"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_NO_CHASIS" class="col-xs-3 control-label">No. Chasis</label>
                        <div class="col-xs-9">
                            <div class="input-group">
                                <?php echo CHtml::textField('FORM[NO_CHASIS]', '', array('class' => 'form-control text-besar', 'placeholder' => 'No. Chasis')); ?>
                                <div class="input-group-addon">
                                    <a href="javascript:void(0)" onclick="prosesSearchDetailSb('<?php echo $this->createUrl('Default/DetailNoSb'); ?>', 'no_chasis')"><i class="glyphicon glyphicon-search"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_TGL_MATI_UJI" class="col-xs-3 control-label">Tgl. Mati Uji</label>
                        <div class="col-xs-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="FORM[TGL_MATI_UJI]" id="FORM_TGL_MATI_UJI" type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control datemask" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                            </div><!-- /.input group -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_TGL_PENGUJIAN" class="col-xs-3 control-label">Tgl. Pengujian</label>
                        <div class="col-xs-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="FORM[TGL_PENGUJIAN]" id="FORM_TGL_PENGUJIAN" type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control datemask" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                            </div><!-- /.input group -->
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="FORM_NO_KTP" class="col-xs-3 control-label">No. KTP</label>
                        <div class="col-xs-9">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <select class="btn" id="pilih_jenis_pengenal" name="FORM[JENIS_PENGENAL]">
                                        <option value="KTP" selected="selected">KTP</option>
                                        <option value="NIB">NIB</option>
                                    </select>
                                </span>
                                <input type="text" name="FORM[NO_KTP]" id="FORM_NO_KTP" class="form-control text-besar" placeholder=".....">
                            </div>
                            <?php // echo CHtml::textField('FORM[NO_KTP]', '', array('class' => 'form-control text-besar', 'placeholder' => 'No. KTP')); 
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_NO_STUK" class="col-xs-3 control-label">Nama Pemilik</label>
                        <div class="col-xs-9">
                            <?php echo CHtml::textField('FORM[NAMA_PEMILIK]', '', array('class' => 'form-control text-besar', 'placeholder' => 'Nama Pemilik')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_ALAMAT" class="col-xs-3 control-label">Alamat</label>
                        <div class="col-xs-9">
                            <?php echo CHtml::textArea('FORM[ALAMAT]', '', array('class' => 'form-control text-besar', 'rows' => '3', 'placeholder' => 'Alamat')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_NO_TELP" class="col-xs-3 control-label">No. Telp</label>
                        <div class="col-xs-9">
                            <?php echo CHtml::textField('FORM[NO_TELP]', '', array('class' => 'form-control text-besar', 'placeholder' => 'No. Telp',)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_BUKU_UJI" class="col-xs-3 control-label">Kartu Uji</label>
                        <div class="col-xs-6 form-group">
                            <?php
                            $tbl_bk_uji = TblBkMasuk::model()->findAll();
                            foreach ($tbl_bk_uji as $i => $bk_uji) :
                            ?>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio">
                                        <input value="<?php echo $bk_uji->id_bk_masuk; ?>" class="custom-control-input custom-control-input-danger" type="radio" id="FORM_BUKU_UJI<?php echo $bk_uji->id_bk_masuk; ?>" name="FORM[BUKU_UJI]" <?php echo ($bk_uji->id_bk_masuk == '1') ? 'checked' : ''; ?>>
                                        <label for="FORM_BUKU_UJI<?php echo $bk_uji->id_bk_masuk; ?>" class="custom-control-label"><?php echo ucwords(strtolower($bk_uji->bk_masuk)); ?></label>
                                    </div>
                                </div>
                            <?php $i++;
                            endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="FORM_SCAN" class="col-xs-3 control-label">Scan File</label>
                        <div class="col-xs-9">
                            <span id="replace_file"><input type="file" name="files" id="file"></span>
                            <span id="cobalah"></span>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-xs-12">
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary" onclick="submitForm('<?php echo $this->createUrl('Default/SaveForm'); ?>', 'formPendaftaran')">DAFTAR</button>
                                <button type="button" class="btn btn-danger" onclick="buttonReset('formPendaftaran')">RESET</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo CHtml::endForm(); ?>

            </div><!-- /.box-body-->
        </div><!-- /.box .box-info-->
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Validasi</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php echo $this->renderPartial('list_retribusi'); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on("keypress", '#FORM_NO_STUK', function(e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            prosesSearchDetailSb('<?php echo $this->createUrl('Default/DetailNoSb'); ?>', 'sb');
            return false;
        }
    });
    $(document).on("keypress", '#FORM_NO_KENDARAAN', function(e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            prosesSearchDetailSb('<?php echo $this->createUrl('Default/DetailNoSb'); ?>', 'no_kend');
            return false;
        }
    });
    $(document).on("keypress", '#FORM_NO_CHASIS', function(e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            prosesSearchDetailSb('<?php echo $this->createUrl('Default/DetailNoSb'); ?>', 'no_chasis');
            return false;
        }
    });
    $(document).on("keypress", '#FORM_NO_MESIN', function(e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            prosesSearchDetailSb('<?php echo $this->createUrl('Default/DetailNoSb'); ?>', 'no_mesin');
            return false;
        }
    });

    function selectJenisUji() {
        var jenis_uji = $('#FORM_JENIS_PENGUJIAN :selected').val();

        if (jenis_uji == 2 || jenis_uji == 4) {
            $("#div_wilayah_asal").show();
            $("#div_kartu_hilang").hide();
        } else if (jenis_uji == 11) {
            $("#div_wilayah_asal").hide();
            $("#div_kartu_hilang").show();
        } else {
            $("#div_kartu_hilang").hide();
            $("#div_wilayah_asal").hide();
        }

        if (jenis_uji == 11) {
            //BUKU HILANG
            $('#FORM_BUKU_UJI1').iCheck('uncheck');
            $('#FORM_BUKU_UJI1').iCheck('update');

            $('#FORM_BUKU_UJI2').iCheck('uncheck');
            $('#FORM_BUKU_UJI2').iCheck('update');

            $('#FORM_BUKU_UJI3').iCheck('check');
            $('#FORM_BUKU_UJI3').iCheck('update');

            $('#FORM_BUKU_UJI4').iCheck('uncheck');
            $('#FORM_BUKU_UJI4').iCheck('update');
        } else if (jenis_uji == 10) {
            //BUKU RUSAK
            $('#FORM_BUKU_UJI1').iCheck('uncheck');
            $('#FORM_BUKU_UJI1').iCheck('update');

            $('#FORM_BUKU_UJI2').iCheck('uncheck');
            $('#FORM_BUKU_UJI2').iCheck('update');

            $('#FORM_BUKU_UJI3').iCheck('uncheck');
            $('#FORM_BUKU_UJI3').iCheck('update');

            $('#FORM_BUKU_UJI4').iCheck('check');
            $('#FORM_BUKU_UJI4').iCheck('update');
        } else if (jenis_uji == 8 || jenis_uji == 4) {
            $('#FORM_BUKU_UJI1').iCheck('uncheck');
            $('#FORM_BUKU_UJI1').iCheck('update');

            $('#FORM_BUKU_UJI2').iCheck('check');
            $('#FORM_BUKU_UJI2').iCheck('update');

            $('#FORM_BUKU_UJI3').iCheck('uncheck');
            $('#FORM_BUKU_UJI3').iCheck('update');

            $('#FORM_BUKU_UJI4').iCheck('uncheck');
            $('#FORM_BUKU_UJI4').iCheck('update');
        } else {
            $('#FORM_BUKU_UJI1').iCheck('check');
            $('#FORM_BUKU_UJI1').iCheck('update');

            $('#FORM_BUKU_UJI2').iCheck('uncheck');
            $('#FORM_BUKU_UJI2').iCheck('update');

            $('#FORM_BUKU_UJI3').iCheck('uncheck');
            $('#FORM_BUKU_UJI3').iCheck('update');

            $('#FORM_BUKU_UJI4').iCheck('uncheck');
            $('#FORM_BUKU_UJI4').iCheck('update');
        }
    }
</script>