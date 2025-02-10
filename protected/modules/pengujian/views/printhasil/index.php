<style>
    .datagrid-row {
        min-height: 40px;
        height: 40px;
    }

    #button_save_lulus {
        background: #E53935;
        color: #fff;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Cetak Print Hasil</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="col-lg-2 col-md-3">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                                <?php echo CHtml::textField('tgl_search', date('d-M-Y'), array('readonly' => 'readonly', 'class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <input type="text" id="text_category" class="text-besar form-control" placeholder="NO UJI / NO KENDARAAN">
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <select id="choose_kelulusan" class="form-control" onchange="prosesSearch()">
                                <option value="all" selected="true">- Semua L/TL-</option>
                                <option value="true">Lulus</option>
                                <option value="false">Tidak Lulus</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <select id="choose_cetak" class="form-control" onchange="prosesSearch()">
                                <option value="all">- Semua Cetak/Belum-</option>
                                <option value="false" selected="true">Belum Cetak</option>
                                <option value="true">Sudah Cetak</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <button type="button" class="btn btn-info" onclick="prosesSearch()">
                                <span class="glyphicon glyphicon-refresh"></span> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12" style="margin-top: 20px">
                        <table id="statusProsesListGrid"></table>
                    </div>
                    <div class="col-lg-12 col-md-12 no-padding" style="margin-top: 10px;">
                        <div class="col-lg-3 col-xs-6">
                            <img class="img-thumbnail" id="img_depan">
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <img class="img-thumbnail" id="img_belakang">
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <img class="img-thumbnail" id="img_kiri">
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <img class="img-thumbnail" id="img_kanan">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="dlg_no_surat" class="easyui-dialog" title="No Surat" style="width: 400px; height: auto; padding: 10px;display: none" data-options="
     iconCls: 'icon-print',
     autoOpen: false,
     buttons: [{
     text:'Print',
     iconCls:'icon-print',
     handler:function(){
     cetakTidakLulus();
     }
     }]
     ">
        <div class="col-lg-12 col-md-12 col-sm-12 no-padding">
            <div class="col-lg-12 col-md-12 col-sm-12 no-padding" style="margin-bottom: 10px;">
                <select id="choose_penguji" class="form-control">
                    <?php
                    // $penguji = Penguji::model()->findAll();
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("job_name ilike '%penguji%'");
                    $penguji = MasterEmployee::model()->findAll($criteria);
                    foreach ($penguji as $dataPenguji) :
                    ?>
                        <option value="<?php echo $dataPenguji->user_id; ?>"><?php echo $dataPenguji->full_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 no-padding">
                <?php
                echo CHtml::hiddenField('dialog_url', '');
                echo CHtml::hiddenField('dialog_id', '');
                // echo CHtml::textField('dialog_no_surat', '', array('class' => 'form-control text-besar'));
                ?>
            </div>
        </div>
    </div>
    <div id="dlg_lulus_sementara" class="easyui-dialog" title="Lulus Sementara" style="width: 400px; height: auto; padding: 10px;display: none" data-options="
     iconCls: 'icon-print',
     autoOpen: false,
     buttons: [{
     text:'Print',
     iconCls:'icon-print',
     handler:function(){
     cetakLulusSementara();
     }
     }]
     ">
        <div class="col-lg-12 col-md-12 col-sm-12 no-padding">
            <div class="col-lg-12 col-md-12 col-sm-12 no-padding" style="margin-bottom: 10px;">
                <select id="choose_penguji_sementara" class="form-control">
                    <?php
                    // $penguji = Penguji::model()->findAll();
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("job_name ilike '%penguji%'");
                    $penguji = MasterEmployee::model()->findAll($criteria);
                    foreach ($penguji as $dataPenguji) :
                    ?>
                        <option value="<?php echo $dataPenguji->user_id; ?>"><?php echo $dataPenguji->full_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 no-padding">
                <?php
                echo CHtml::textArea('catatan_lulus_sementara', '', array('class' => 'form-control'));
                echo CHtml::hiddenField('dialog_sementara_url', '');
                echo CHtml::hiddenField('dialog_sementara_id', '');
                ?>
            </div>
        </div>
    </div>
    <div id="dlg_cetak_hasil" class="easyui-dialog" title="Cetak Hasil Uji" style="width: 400px; height: auto; padding: 10px;display: none" data-options="
     iconCls: 'icon-print',
     autoOpen: false,
     buttons: [{
     id: 'button_save_lulus',
     text:'Kartu',
     iconCls:'icon-save',
     handler:function(){
     submitLulus();
     }}]
     ">
        <div class="col-lg-12 col-md-12 col-sm-12 no-padding">
            <div class="col-lg-12 col-md-12 col-sm-12 no-pad
            ding" style="margin-bottom: 10px;">
                <?php echo CHtml::hiddenField('dialog_lulus_id', ''); ?>
                <select id="choose_lulus_penguji" class="form-control">
                    <?php
                    // $penguji = Penguji::model()->findAll();
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("job_name ilike '%penguji%'");
                    $penguji = MasterEmployee::model()->findAll($criteria);
                    foreach ($penguji as $dataPenguji) :
                    ?>
                        <option value="<?php echo $dataPenguji->user_id; ?>"><?php echo $dataPenguji->full_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div id="dlg_cetak_non_full" class="easyui-dialog" title="Cetak Hasil Uji Non-FullCycle" style="width: 400px; height: auto; padding: 10px;display: none" data-options="
     iconCls: 'icon-print',
     autoOpen: false,
     buttons: [{
     id: 'button_save_lulus',
     text:'Kartu',
     iconCls:'icon-save',
     handler:function(){
     submitLulusNonFull();
     }}]
     ">
        <div class="col-lg-12 col-md-12 col-sm-12 no-padding">
            <div class="col-lg-12 col-md-12 col-sm-12 no-padding" style="margin-bottom: 10px;">
                <?php echo CHtml::hiddenField('dlg_cetak_non_full_id', ''); ?>
                <select id="dlg_cetak_non_full_penguji" class="form-control">
                    <?php
                    $penguji = Penguji::model()->findAll();
                    foreach ($penguji as $dataPenguji) :
                    ?>
                        <option value="<?php echo $dataPenguji->nrp; ?>">
                            <?php echo $dataPenguji->nama; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div id="dlg_cetak_tl_dimensi" class="easyui-dialog" title="Cetak TL Dimensi" style="width: 400px; height: auto; padding: 10px;display: none" data-options="
     iconCls: 'icon-print',
     autoOpen: false,
     modal: true,
     buttons: [{
     text:'Print',
     iconCls:'icon-print',
     handler:function(){
     cetakTlDimensi();
     }
     }]
     ">
        <div class="col-lg-12 col-md-12 col-sm-12 no-padding">
            <div class="col-lg-12 col-md-12 col-sm-12 no-padding" style="margin-bottom: 10px;">
                <?php
                echo CHtml::hiddenField('dialog_tl_dimensi_id', '');
                ?>
                <select id="choose_tl_penguji" class="form-control">
                    <?php
                    // $penguji = Penguji::model()->findAll();
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("job_name ilike '%penguji%'");
                    $penguji = MasterEmployee::model()->findAll($criteria);
                    foreach ($penguji as $dataPenguji) :
                    ?>
                        <option value="<?php echo $dataPenguji->user_id; ?>"><?php echo $dataPenguji->full_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <script>
        $('#statusProsesListGrid').datagrid({
            url: '<?php echo $this->createUrl('Printhasil/PrintHasilListGrid'); ?>',
            width: '100%',
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: true,
            collapsible: true,
            rownumbers: true,
            striped: true,
            loadMsg: 'Loading...',
            method: 'POST',
            nowrap: false,
            pageNumber: 1,
            pageSize: 15,
            pageList: [15, 30, 50],
            columns: [
                [
                    <?php
                    // if (Yii::app()->user->isRole('Admin')) { 
                    ?>
                    // {
                    //     field: 'banding_prauji',
                    //     title: 'Prauji',
                    //     width: 50,
                    //     halign: 'center',
                    //     align: 'center',
                    //     formatter: bandingPraujiButton
                    // },
                    // {
                    //     field: 'banding_pengukuran',
                    //     title: 'Pengukuran',
                    //     width: 50,
                    //     halign: 'center',
                    //     align: 'center',
                    //     formatter: bandingPengukuranButton
                    // },
                    <?php
                    // } 
                    ?> {
                        field: 'foto',
                        title: 'Foto',
                        width: 50,
                        halign: 'center',
                        align: 'center',
                        formatter: buttonImage
                    },
                    {
                        field: 'cetak',
                        width: 50,
                        title: 'Cetak',
                        sortable: false,
                        halign: 'center',
                        align: 'center',
                        formatter: buttonCetak
                    },
                    {
                        field: 'cetak_nonfull',
                        width: 50,
                        title: 'Cetak <br/> non Full',
                        sortable: false,
                        halign: 'center',
                        align: 'center',
                        formatter: buttonCetakNonFull
                    },
                    {
                        field: 'no_kendaraan',
                        width: 90,
                        title: 'No Kendaraan',
                        sortable: false
                    },
                    {
                        field: 'no_uji',
                        title: 'No Uji',
                        width: 100,
                        sortable: false
                    },
                    {
                        field: 'nama_pemilik',
                        title: 'Nama Pemilik',
                        width: 150,
                        sortable: false
                    },
                    {
                        field: 'jenis_kendaraan',
                        title: 'Jenis Kendaraan',
                        width: 200,
                        sortable: false
                    },
                    {
                        field: 'kartu_uji',
                        title: 'Kartu Uji',
                        width: 80,
                        sortable: false
                    },
                    {
                        field: 'nm_uji',
                        title: 'Jenis Uji',
                        width: 80,
                        sortable: false
                    },
                    {
                        field: 'prauji',
                        width: 50,
                        title: 'Pra Uji',
                        sortable: false,
                        halign: 'center',
                        align: 'center'
                    },
                    {
                        field: 'emisi',
                        width: 50,
                        title: 'Emisi',
                        sortable: false,
                        halign: 'center',
                        align: 'center'
                    },
                    {
                        field: 'pitlift',
                        width: 50,
                        title: 'Pit Lift',
                        sortable: false,
                        halign: 'center',
                        align: 'center'
                    },
                    {
                        field: 'lampu',
                        width: 50,
                        title: 'Lampu',
                        sortable: false,
                        halign: 'center',
                        align: 'center'
                    },
                    {
                        field: 'rem',
                        width: 50,
                        title: 'Brake',
                        sortable: false,
                        halign: 'center',
                        align: 'center'
                    },
                    {
                        field: 'nm_penguji',
                        width: 150,
                        title: 'Penguji',
                        sortable: false,
                        halign: 'center'
                    },
                    {
                        field: 'catatan',
                        width: 250,
                        title: 'Catatan',
                        sortable: false,
                        halign: 'center'
                    }
                ]
            ],
            onBeforeLoad: function(params) {
                params.tanggal = $('#tgl_search').val();
                params.chooseKelulusan = $('#choose_kelulusan :selected').val();
                params.chooseCetak = $('#choose_cetak :selected').val();
                params.textCategory = $('#text_category').val();
            },
            onLoadError: function() {
                return false;
            },
            onLoadSuccess: function() {}
        });

        $(document).ready(function() {
            $('#dlg_no_surat').dialog('close');
            $('#dlg_cetak_hasil').dialog('close');
            $('#dlg_cetak_non_full').dialog('close');
            $('#dlg_cetak_tl_dimensi').dialog('close');
            $('#dlg_lulus_sementara').dialog('close');
            $('#tgl_search').datepicker({
                endDate: "today",
                format: 'dd-M-yyyy',
                daysOfWeekDisabled: [0, 6],
                autoclose: true,
            }).on('changeDate', prosesSearch);
        });

        $(document).on("keypress", '#text_category', function(e) {
            var code = e.keyCode || e.which;
            if (code == 13) {
                prosesSearch();
                return false;
            }
        });

        $("#dlg_cetak_hasil").keydown(function(event) {
            if (event.keyCode == 13) {
                $(this).parent()
                    .find("#button_print_lulus").trigger("click");
                return false;
            }
        });

        function bandingPraujiButton(value) {
            var button;
            var urlact = '<?php echo $this->createUrl('Printhasil/ProsesBandingPrauji'); ?>';
            button = '<button type="button" data-toggle="tooltip" title="Banding" class="btn btn-danger" onclick="buttonBanding(' + value + ', \'' + urlact + '\')"><span class="glyphicon glyphicon-random"></span></button>';
            return button;
        }

        function bandingPengukuranButton(value) {
            var button;
            var urlact = '<?php echo $this->createUrl('Printhasil/ProsesBandingPengukuran'); ?>';
            button = '<button type="button" data-toggle="tooltip" title="Banding" class="btn btn-danger" onclick="buttonBanding(' + value + ', \'' + urlact + '\')"><span class="glyphicon glyphicon-random"></span></button>';
            return button;
        }

        function prosesSearch() {
            $('#statusProsesListGrid').datagrid('reload');
        }

        function buttonBanding(value, urlAct) {
            $.messager.confirm('Confirm', 'Apakah anda yakin ingin banding?', function(r) {
                if (r) {
                    $.ajax({
                        type: 'POST',
                        url: urlAct,
                        data: {
                            id_hasil_uji: value
                        },
                        beforeSend: function() {
                            showlargeloader();
                        },
                        success: function(data) {
                            $('#statusProsesListGrid').datagrid('reload');
                            hidelargeloader();
                        },
                        error: function() {
                            $('#statusProsesListGrid').datagrid('reload');
                            hidelargeloader();
                            return false;
                        }
                    });
                }
            });
        }

        function buttonCetak(value) {
            var button;
            var explode = value.split('|');
            var ltl = explode[0];
            var id = explode[1];
            var noSurat = explode[2];
            if (ltl == 'l') {
                button = '<button class="btn btn-success" type="button" onclick="buttonDialogPosisi(\'' + id + '\')"><span class="glyphicon glyphicon-duplicate"></span></button>';
            } else if (ltl == 'tl') {
                var url = '<?php echo $this->createUrl('Printhasil/CetakTidakLulus'); ?>';
                button = '<button class="btn btn-danger" type="button" onclick="buttonDialogNoSurat(\'' + url + '\', \'' + id + '\', \'' + noSurat + '\')"><span class="glyphicon glyphicon-duplicate"></span></button>';
            } else {
                button = "<button class='btn btn-default' type='button' disabled='disabled'><span class='glyphicon glyphicon-duplicate'></span></button>";
            }
            return button;
        }

        function buttonCetakNonFull(value) {
            var button;
            var explode = value.split('|');
            var ltl = explode[0];
            var id = explode[1];
            var noSurat = explode[2];
            var url = '';
            if (ltl == 'l') {
                button = '<button class="btn btn-success" type="button" onclick="buttonDialogNonFull(\'' + id + '\')"><span class="glyphicon glyphicon-duplicate"></span></button>';
            } else {
                button = "<button class='btn btn-default' type='button' disabled='disabled'><span class='glyphicon glyphicon-duplicate'></span></button>";
            }
            return button;
        }

        // PROSES CETAK LULUS SEMENTARA
        function buttonCetakSementara(value) {
            var button;
            var explode = value.split('|');
            var ltl = explode[0];
            var id = explode[1];
            var url = '';
            if (ltl == 'l') {
                url = '<?php echo $this->createUrl('Printhasil/CetakLulusSementara'); ?>';
                button = '<button class="btn btn-success" type="button" onclick="buttonDialogNoSuratSementara(\'' + url + '\', \'' + id + '\')"><span class="glyphicon glyphicon-duplicate"></span></button>';
            } else {
                button = "<button class='btn btn-default' type='button' disabled='disabled'><span class='glyphicon glyphicon-duplicate'></span></button>";
            }
            return button;
        }

        function buttonDialogNoSuratSementara(urlAct, id) {
            $('#dialog_sementara_id').val(id);
            $('#dialog_sementara_url').val(urlAct);
            $('#dlg_lulus_sementara').dialog('open');
        }

        function cetakLulusSementara() {
            var no_surat = '';
            var penguji = $("#choose_penguji_sementara option:selected").val();
            var catatan = $('#catatan_lulus_sementara').val();
            var id = $('#dialog_sementara_id').val();
            var url = $('#dialog_sementara_url').val();
            $.ajax({
                url: '<?php echo $this->createUrl('Printhasil/SaveCetakLulusSementara'); ?>',
                type: 'POST',
                data: {
                    no_surat: no_surat,
                    penguji: penguji,
                    id: id,
                    catatan: catatan
                },
                success: function(data) {
                    $('#dlg_lulus_sementara').dialog('close');
                    prosesSearch();
                    prosesCetakLulusSementara(url, id, no_surat, penguji);
                },
                error: function(data) {
                    $('#dlg_lulus_sementara').dialog('close');
                    prosesSearch();
                    return false;
                }
            });
        }

        function prosesCetakLulusSementara(url, id, no_surat, penguji) {
            var win = window.open(url + "?id=" + id + "&nosurat=" + no_surat + "&nrp=" + penguji, '_blank');
            win.focus();
        }

        // PROSES CETAK LULUS
        function buttonDialogPosisi(id) {
            $('#dialog_lulus_id').val(id);
            $('#dlg_cetak_non_full').dialog('close');
            $('#dlg_cetak_hasil').dialog('open');
            $('#dlg_cetak_hasil').dialog('center');
        }

        function buttonDialogNonFull(id) {
            $('#dlg_cetak_non_full_id').val(id);
            $('#dlg_cetak_hasil').dialog('close');
            $('#dlg_cetak_non_full').dialog('open');
            $('#dlg_cetak_non_full').dialog('center');
        }

        function submitLulus() {
            var penguji = $("#choose_lulus_penguji option:selected").val();
            var id = $('#dialog_lulus_id').val();
            $.ajax({
                url: '<?php echo $this->createUrl('Printhasil/SaveCetakLulus'); ?>',
                type: 'POST',
                data: {
                    penguji: penguji,
                    id: id,
                },
                success: function(data) {
                    $('#dlg_cetak_hasil').dialog('close');
                    prosesSearch();
                },
                error: function(data) {
                    $('#dlg_cetak_hasil').dialog('close');
                    prosesSearch();
                    return false;
                }
            });
        }

        function submitLulusNonFull() {
            var penguji = $("#dlg_cetak_non_full_penguji option:selected").val();
            var id = $('#dlg_cetak_non_full_id').val();
            $.ajax({
                url: '<?php echo $this->createUrl('Printhasil/SaveCetakLulusNonFull'); ?>',
                type: 'POST',
                data: {
                    penguji: penguji,
                    id: id,
                },
                success: function(data) {
                    $('#dlg_cetak_non_full').dialog('close');
                    prosesSearch();
                },
                error: function(data) {
                    $('#dlg_cetak_non_full').dialog('close');
                    prosesSearch();
                    return false;
                }
            });
        }

        // PROSES CETAK TIDAK LULUS
        function buttonDialogNoSurat(urlAct, id, noSurat) {
            $('#dialog_id').val(id);
            $('#dialog_url').val(urlAct);
            $('#dialog_no_surat').val(noSurat);
            $('#dlg_no_surat').dialog('open');
        }

        function cetakTidakLulus() {
            var no_surat = '';
            var penguji = $("#choose_penguji option:selected").val();
            var id = $('#dialog_id').val();
            var url = $('#dialog_url').val();
            $('#dlg_no_surat').dialog('close');
            prosesSearch();
            var win = window.open(url + "?id=" + id + "&nosurat=" + no_surat + "&nrp=" + penguji, '_blank');
            win.focus();
        }

        function buttonImage(value) {
            var urlAct = '<?php echo $this->createUrl('Printhasil/ViewImage'); ?>';
            var button = '<button type="button" class="btn btn-primary" onclick="viewImage(' + value + ', \'' + urlAct + '\')"><span class="glyphicon glyphicon-picture"></span></button>';
            return button;
        }

        function viewImage(idHasilUji, urlAct) {
            $.ajax({
                url: urlAct,
                type: 'POST',
                data: {
                    idHasilUji: idHasilUji
                },
                dataType: 'JSON',
                beforeSend: function() {
                    showlargeloader();
                },
                success: function(data) {
                    hidelargeloader();
                    $("#img_depan").attr('src', 'data:image/jpeg;base64,' + data.img_depan);
                    $("#img_belakang").attr('src', 'data:image/jpeg;base64,' + data.img_belakang);
                    $("#img_kanan").attr('src', 'data:image/jpeg;base64,' + data.img_kanan);
                    $("#img_kiri").attr('src', 'data:image/jpeg;base64,' + data.img_kiri);
                    var bottom = $(document).height() - $(window).height();
                    $('html, body').stop().animate({
                        scrollTop: $("#img_depan").offset().top
                    }, 1500, 'swing');
                },
                error: function(data) {
                    hidelargeloader();
                    return false;
                }
            });
        }
    </script>