<?php

    function CheckGatePass($barcode) {

        if(isset($barcode)) :
            $data = \App\Models\Barcode::where('barcode', $barcode)->first();

            if($data){
    //            Expired
                $exp_date = $data->expired;
                if(date('Y-m-d') > $exp_date){
                    $data_barcode->status = 'expired';
                    return json_encode(array('status' => 'expired', 'code' => 'e'));
                }

                return json_encode(array('status' => $data->status, 'code' => substr($data->status, 0, 1)));
            }else{
                return json_encode(array('status' => 'not found', 'code' => 'f'));
            }
        else :
//                return json_encode(array('msg' => 'wrong parameters'));
            return new \soap_fault('Client', '', 'Barcode is Null', '');
        endif;
    }

    function SendDataGate($barcode, $tipe, $time, $fileKamera) {

        if(!isset($barcode) || !isset($tipe) || !isset($time)){
            return json_encode(array('msg' => 'Something wrong!!! Some parameters not detected.'));
        }
        
        // update barcode
        if($tipe == 'in'){
            \App\Models\Barcode::where('barcode', $barcode)->update(['time_in' => $time]);
        }else{
            \App\Models\Barcode::where('barcode', $barcode)->update(['time_out' => $time]);
        }

        $data_barcode = \App\Models\Barcode::where('barcode', $barcode)->first();
        
        if($data_barcode) {
            $picture = array();
            if($fileKamera){
                $picture = explode('|', $fileKamera);
                if($tipe == 'in'){
                    $data_barcode->photo_in = @serialize($picture);
                }else{
                    $data_barcode->photo_out = @serialize($picture);
                }

                $data_barcode->save();
            }

            switch ($data_barcode->ref_type) {
                case 'Fcl':
                    $model = \App\Models\Containercy::find($data_barcode->ref_id);
                    $ref_number = $model->REF_NUMBER;
                    break;
                case 'Lcl':
                    $model = \App\Models\Container::find($data_barcode->ref_id);
                    $ref_number = $model->REF_NUMBER_IN;
                    break;
                case 'Manifest':
                    $model = \App\Models\Manifest::find($data_barcode->ref_id);
                    break;
            }
            
            if($model){
                
                if($data_barcode->ref_action == 'get'){
                    if($data_barcode->time_in != NULL){
                        // GATEIN
                        $model->TGLMASUK = date('Y-m-d', strtotime($data_barcode->time_in));
                        $model->JAMMASUK = date('H:i:s', strtotime($data_barcode->time_in));
                        if($tipe == 'in'){
                            $model->photo_get_in = @serialize($picture);
                        }else{
                            $model->photo_get_out = @serialize($picture);
                        }
                        $model->UIDMASUK = 'Autogate';

                        if($model->save()){
                            // Update Manifest If LCL
                            if($data_barcode->ref_type == 'Lcl'){
                                \App\Models\Manifest::where('TCONTAINER_FK', $model->TCONTAINER_PK)->update(array('tglmasuk' => $model->TGLMASUK, 'jammasuk' => $model->JAMMASUK));
                            }

//                                return $model->NOCONTAINER.' '.$data_barcode->ref_type.' '.$data_barcode->ref_action.' Updated';
//                                $callback = array(
//                                    'm' => $model->NOCONTAINER.' '.$data_barcode->ref_type.' '.$data_barcode->ref_action.' Updated',
//                                    'd' => array(
//                                        'nocont' => $model->NOCONTAINER,
//                                        'nopol' => $model->NOPOL,
//                                        'noplp' => $model->NO_PLP,
//                                        'tglplp' => $model->TGL_PLP,
//                                        'nobc11' => $model->NO_BC11,
//                                        'tglbc11' => $model->TGL_BC11,
//                                        'kegiatan' => 'masuk',
//                                        'tipe' => $data_barcode->ref_type
//                                    )
//                                );
//                                
//                                return json_encode($callback);
                                return "nocont:".$model->NOCONTAINER.",nopol:".$model->NOPOL.",noplp:".$model->NO_PLP.",tglplp:".$model->TGL_PLP.",nobc11:".$model->NO_BC11.",tglbc11:".$model->TGL_BC11.",kegiatan:masuk,tipe:".$data_barcode->ref_type;
  
                        }else{
                            return json_encode(array('msg' => 'Something wrong!!! Cannot store to database'));
                        }
                    }else{
                        return json_encode(array('msg' => 'Time In is NULL'));
                    }
                }elseif($data_barcode->ref_action == 'release'){
//                    if($data_barcode->time_out != NULL){
                        // RELEASE
                        if($tipe == 'out'){
                            if($model->status_bc == 'HOLD' || $model->flag_bc == 'Y'):
                                return json_encode(array('msg' => 'Status BC is HOLD or FLAGING, please unlock!!!'));
                            endif;
                        }
                    
                        if($data_barcode->ref_type == 'Manifest'){
                            if($data_barcode->time_out){
                                $model->tglrelease = date('Y-m-d', strtotime($data_barcode->time_out));
                                $model->jamrelease = date('H:i:s', strtotime($data_barcode->time_out));
                                $model->UIDRELEASE = 'Autogate';
                                $model->TGLSURATJALAN = date('Y-m-d', strtotime($data_barcode->time_out));
                                $model->JAMSURATJALAN = date('H:i:s', strtotime($data_barcode->time_out));
                                $model->tglfiat = date('Y-m-d', strtotime($data_barcode->time_out));
                                $model->jamfiat = date('H:i:s', strtotime($data_barcode->time_out));
                                $model->NAMAEMKL = 'Autogate';
                                $model->UIDSURATJALAN = 'Autogate';
                            }
                            if($tipe == 'in'){
                                $model->photo_release_in = @serialize($picture);
                            }else{
                                $model->photo_release_out = @serialize($picture);
                            }

                            if($model->save()){
//                                return $model->NOHBL.' '.$data_barcode->ref_type.' '.$data_barcode->ref_action.' Updated';
//                                $callback = array(
//                                    'm' => $model->NOCONTAINER.' '.$data_barcode->ref_type.' '.$data_barcode->ref_action.' Updated',
//                                    'd' => array(
//                                        'nohbl' => $model->NOHBL,
//                                        'nopol' => $model->NOPOL_RELEASE,
//                                        'noplp' => $model->NO_PLP,
//                                        'tglplp' => $model->TGL_PLP,
//                                        'nobc11' => $model->NO_BC11,
//                                        'tglbc11' => $model->TGL_BC11,
//                                        'kegiatan' => 'keluar',
//                                        'tipe' => $data_barcode->ref_type
//                                    )
//                                );
//                                
//                                return json_encode($callback);
                                if($tipe == 'out'){
                                    $data_barcode->status = 'inactive';
                                    $data_barcode->save();
                                }
                                return "nohbl:".$model->NOHBL.",nopol:".$model->NOPOL_RELEASE.",noplp:".$model->NO_PLP.",tglplp:".$model->TGL_PLP.",nobc11:".$model->NO_BC11.",tglbc11:".$model->TGL_BC11.",kegiatan:keluar,tipe:".$data_barcode->ref_type;
                            }else{
                                return json_encode(array('msg' => 'Something wrong!!! Cannot store to database'));
                            }
                        }else{
                            if($data_barcode->time_out){
                                $model->TGLRELEASE = date('Y-m-d', strtotime($data_barcode->time_out));
                                $model->JAMRELEASE = date('H:i:s', strtotime($data_barcode->time_out));
                                $model->UIDKELUAR = 'Autogate';
                                $model->TGLFIAT = date('Y-m-d', strtotime($data_barcode->time_out));
                                $model->JAMFIAT = date('H:i:s', strtotime($data_barcode->time_out));
                                $model->TGLSURATJALAN = date('Y-m-d', strtotime($data_barcode->time_out));
                                $model->JAMSURATJALAN = date('H:i:s', strtotime($data_barcode->time_out));
                            }
                            if($tipe == 'in'){
                                $model->photo_release_in = @serialize($picture);
                            }else{
                                $model->photo_release_out = @serialize($picture);
                            }
                            if($model->save()){

//                          return $model->NOCONTAINER.' '.$data_barcode->ref_type.' '.$data_barcode->ref_action.' Updated';

//                            $callback = array(
//                                    'm' => $model->NOCONTAINER.' '.$data_barcode->ref_type.' '.$data_barcode->ref_action.' Updated',
//                                    'd' => array(
//                                        'nocont' => $model->NOCONTAINER,
//                                        'nopol' => $model->NOPOL_OUT,
//                                        'noplp' => $model->NO_PLP,
//                                        'tglplp' => $model->TGL_PLP,
//                                        'nobc11' => $model->NO_BC11,
//                                        'tglbc11' => $model->TGL_BC11,
//                                        'kegiatan' => 'keluar',
//                                        'tipe' => $data_barcode->ref_type
//                                    )
//                                );
//                                
//                                return json_encode($callback);
                                if($tipe == 'out'){
                                    $data_barcode->status = 'inactive';
                                    $data_barcode->save();
                                }
                                return "nocont:".$model->NOCONTAINER.",nopol:".$model->NOPOL_OUT.",noplp:".$model->NO_PLP.",tglplp:".$model->TGL_PLP.",nobc11:".$model->NO_BC11.",tglbc11:".$model->TGL_BC11.",kegiatan:keluar,tipe:".$data_barcode->ref_type;
                            }else{
                                return json_encode(array('msg' => 'Something wrong!!! Cannot store to database'));
                            }
                        }
//                    }else{
//                        return 'Error';
//                    }
                    
                }elseif($data_barcode->ref_action == 'empty'){
//                    if($data_barcode->time_out != NULL){
                        if($data_barcode->time_out){
                            $model->TGLBUANGMTY = date('Y-m-d', strtotime($data_barcode->time_out));
                            $model->JAMBUANGMTY = date('H:i:s', strtotime($data_barcode->time_out));
                            $model->UIDMTY = 'Autogate';
                        }
                        if($tipe == 'in'){
                            $model->photo_empty_in = @serialize($picture);
                        }else{
                            $model->photo_empty_out = @serialize($picture);
                        }
                        if($model->save()){
//                                return $model->NOCONTAINER.' '.$data_barcode->ref_type.' '.$data_barcode->ref_action.' Updated';
//                            $callback = array(
//                                'm' => $model->NOCONTAINER.' '.$data_barcode->ref_type.' '.$data_barcode->ref_action.' Updated',
//                                'd' => array(
//                                    'nocont' => $model->NOCONTAINER,
//                                    'nopol' => $model->NOPOL_MTY,
//                                    'noplp' => $model->NO_PLP,
//                                    'tglplp' => $model->TGL_PLP,
//                                    'nobc11' => $model->NO_BC11,
//                                    'tglbc11' => $model->TGL_BC11,
//                                    'kegiatan' => 'empty',
//                                    'tipe' => $data_barcode->ref_type
//                                )
//                            );
//
//                            return json_encode($callback);
                            if($tipe == 'out'){
                                $data_barcode->status = 'inactive';
                                $data_barcode->save();
                            }
                            return "nocont:".$model->NOCONTAINER.",nopol:".$model->NOPOL_MTY.",noplp:".$model->NO_PLP.",tglplp:".$model->TGL_PLP.",nobc11:".$model->NO_BC11.",tglbc11:".$model->TGL_BC11.",kegiatan:empty,tipe:".$data_barcode->ref_type;
                    }else{
                        return json_encode(array('msg' => 'Something wrong!!! Cannot store to database'));
                    }
                }

            }else{
                return json_encode(array('msg' => 'Something wrong in Model!!!'));
            }
        }else{
            return json_encode(array('msg' => 'Barcode not found!!'));
        }
    }
