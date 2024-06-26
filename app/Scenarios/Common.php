<?php

namespace App\Scenarios;

class Common
{
    /** ////////////////////////////////////////////
     * ebcdic to shift jis
     * EBCDICをSJISに文字コード変換
     * @param  String ファイルパス
     * * @param  String file_data 
     * @return string if success then String else null
     *//////////////////////////////////////////////
    public function ebcdic_2_sjis($file_path=null,$file_data=null)
    {
        $ebcdic_map = array(
             '00'=>'00', '01'=>'01', '02'=>'02', '03'=>'03', '04'=>'1A', '05'=>'09', '06'=>'1A', '07'=>'7F',
             '08'=>'1A', '09'=>'1A', '0A'=>'1A', '0B'=>'0B', '0C'=>'0C', '0D'=>'0D', '0E'=>'0E', '0F'=>'0F',
             '10'=>'10', '11'=>'11', '12'=>'12', '13'=>'13', '14'=>'1A', '15'=>'1A', '16'=>'08', '17'=>'1A',
             '18'=>'18', '19'=>'19', '1A'=>'1A', '1B'=>'1A', '1C'=>'1C', '1D'=>'1D', '1E'=>'1E', '1F'=>'1F',
             '20'=>'1A', '21'=>'1A', '22'=>'1A', '23'=>'1A', '24'=>'1A', '25'=>'0A', '26'=>'17', '27'=>'1B',
             '28'=>'1A', '29'=>'1A', '2A'=>'1A', '2B'=>'1A', '2C'=>'1A', '2D'=>'05', '2E'=>'06', '2F'=>'07',
             '30'=>'1A', '31'=>'1A', '32'=>'16', '33'=>'1A', '34'=>'1A', '35'=>'1A', '36'=>'1A', '37'=>'04',
             '38'=>'1A', '39'=>'1A', '3A'=>'1A', '3B'=>'1A', '3C'=>'14', '3D'=>'15', '3E'=>'1A', '3F'=>'1A',
             '40'=>'20', '41'=>'A1', '42'=>'A2', '43'=>'A3', '44'=>'A4', '45'=>'A5', '46'=>'A6', '47'=>'A7',
             '48'=>'A8', '49'=>'A9', '4A'=>'5B', '4B'=>'2E', '4C'=>'3C', '4D'=>'28', '4E'=>'2B', '4F'=>'7C',
             '50'=>'26', '51'=>'AA', '52'=>'AB', '53'=>'AC', '54'=>'AD', '55'=>'AE', '56'=>'AF', '57'=>'1A',
             '58'=>'B0', '59'=>'1A', '5A'=>'21', '5B'=>'5C', '5C'=>'2A', '5D'=>'29', '5E'=>'3B', '5F'=>'5E',
             '60'=>'2D', '61'=>'2F', '62'=>'61', '63'=>'62', '64'=>'63', '65'=>'64', '66'=>'65', '67'=>'66',
             '68'=>'67', '69'=>'68', '6A'=>'7C', '6B'=>'2C', '6C'=>'25', '6D'=>'5F', '6E'=>'3E', '6F'=>'3F',
             '70'=>'5B', '71'=>'69', '72'=>'6A', '73'=>'6B', '74'=>'6C', '75'=>'6D', '76'=>'6E', '77'=>'6F',
             '78'=>'70', '79'=>'60', '7A'=>'3A', '7B'=>'23', '7C'=>'40', '7D'=>'27', '7E'=>'3D', '7F'=>'22',
             '80'=>'5D', '81'=>'B1', '82'=>'B2', '83'=>'B3', '84'=>'B4', '85'=>'B5', '86'=>'B6', '87'=>'B7',
             '88'=>'B8', '89'=>'B9', '8A'=>'BA', '8B'=>'71', '8C'=>'BB', '8D'=>'BC', '8E'=>'BD', '8F'=>'BE',
             '90'=>'BF', '91'=>'C0', '92'=>'C1', '93'=>'C2', '94'=>'C3', '95'=>'C4', '96'=>'C5', '97'=>'C6',
             '98'=>'C7', '99'=>'C8', '9A'=>'C9', '9B'=>'72', '9C'=>'1A', '9D'=>'CA', '9E'=>'CB', '9F'=>'CC',
             'A0'=>'7E', 'A1'=>'7E', 'A2'=>'CD', 'A3'=>'CE', 'A4'=>'CF', 'A5'=>'D0', 'A6'=>'D1', 'A7'=>'D2',
             'A8'=>'D3', 'A9'=>'D4', 'AA'=>'D5', 'AB'=>'73', 'AC'=>'D6', 'AD'=>'D7', 'AE'=>'D8', 'AF'=>'D9',
             'B0'=>'5E', 'B1'=>'1A', 'B2'=>'5C', 'B3'=>'74', 'B4'=>'75', 'B5'=>'76', 'B6'=>'77', 'B7'=>'78',
             'B8'=>'79', 'B9'=>'7A', 'BA'=>'DA', 'BB'=>'DB', 'BC'=>'DC', 'BD'=>'DD', 'BE'=>'DE', 'BF'=>'DF',
             'C0'=>'7B', 'C1'=>'41', 'C2'=>'42', 'C3'=>'43', 'C4'=>'44', 'C5'=>'45', 'C6'=>'46', 'C7'=>'47',
             'C8'=>'48', 'C9'=>'49', 'CA'=>'1A', 'CB'=>'1A', 'CC'=>'1A', 'CD'=>'1A', 'CE'=>'1A', 'CF'=>'1A',
             'D0'=>'7D', 'D1'=>'4A', 'D2'=>'4B', 'D3'=>'4C', 'D4'=>'4D', 'D5'=>'4E', 'D6'=>'4F', 'D7'=>'50',
             'D8'=>'51', 'D9'=>'52', 'DA'=>'1A', 'DB'=>'1A', 'DC'=>'1A', 'DD'=>'1A', 'DE'=>'1A', 'DF'=>'1A',
             'E0'=>'24', 'E1'=>'1A', 'E2'=>'53', 'E3'=>'54', 'E4'=>'55', 'E5'=>'56', 'E6'=>'57', 'E7'=>'58',
             'E8'=>'59', 'E9'=>'5A', 'EA'=>'1A', 'EB'=>'1A', 'EC'=>'1A', 'ED'=>'1A', 'EE'=>'1A', 'EF'=>'1A',
             'F0'=>'30', 'F1'=>'31', 'F2'=>'32', 'F3'=>'33', 'F4'=>'34', 'F5'=>'35', 'F6'=>'36', 'F7'=>'37',
             'F8'=>'38', 'F9'=>'39', 'FA'=>'1A', 'FB'=>'1A', 'FC'=>'1A', 'FD'=>'1A', 'FE'=>'1A',
             );
           
             if ($file_path!=null) {
                if (is_file($file_path)) {
                    $file_data = file_get_contents($file_path);
                }else{
                    $file_data =null;
                    // echo "The file $file_path does not exist";
                }
            }
            // return $file_data;
            //  echo($file_data.PHP_EOL);
            $ascii_bin_data=null;
            if ($file_data !=null) {
                $hex_data = bin2hex($file_data);
                $ascii_data = '';
                $string_data = '';
                for ($i = 0; $i < strlen($hex_data); $i++) {
                    $ebcdic_char = $hex_data[$i];
                    $i++;
                    $ebcdic_char .= $hex_data[$i];
                    $ebcdic_char = strtoupper($ebcdic_char);
                
                    $ascii_char = $ebcdic_map[$ebcdic_char];
                    $ascii_data .= $ascii_char;
                }

                // $ascii_bin_data = hex2bin($ascii_data);
                $ascii_bin_data = pack('H*', $ascii_data);
            }
            return $ascii_bin_data;
    }

    /** ////////////////////////////////////////////
     * shift jis to ebcdic
     * SJISをEBCDICに文字コード変換
     * @param  String file_path ファイルパス
     * @param  String file_data 
     * @return string if success then String else null
     *//////////////////////////////////////////////
    public function sjis_2_ebcdic($file_path=null,$file_data=null)
    {
        $ebcdic_map = array(
             '00'=>'00', '01'=>'01', '02'=>'02', '03'=>'03', '1A'=>'04', '09'=>'05', '1A'=>'06', '7F'=>'07',
             '1A'=>'08', '1A'=>'09', '1A'=>'0A', '0B'=>'0B', '0C'=>'0C', '0D'=>'0D', '0E'=>'0E', '0F'=>'0F',
             '10'=>'10', '11'=>'11', '12'=>'12', '13'=>'13', '1A'=>'14', '1A'=>'15', '08'=>'16', '1A'=>'17',
             '18'=>'18', '19'=>'19', '1A'=>'1A', '1A'=>'1B', '1C'=>'1C', '1D'=>'1D', '1E'=>'1E', '1F'=>'1F',
             '1A'=>'20', '1A'=>'21', '1A'=>'22', '1A'=>'23', '1A'=>'24', '0A'=>'25', '17'=>'26', '1B'=>'27',
             '1A'=>'28', '1A'=>'29', '1A'=>'2A', '1A'=>'2B', '1A'=>'2C', '05'=>'2D', '06'=>'2E', '07'=>'2F',
             '1A'=>'30', '1A'=>'31', '16'=>'32', '1A'=>'33', '1A'=>'34', '1A'=>'35', '1A'=>'36', '04'=>'37',
             '1A'=>'38', '1A'=>'39', '1A'=>'3A', '1A'=>'3B', '14'=>'3C', '15'=>'3D', '1A'=>'3E', '1A'=>'3F',
             '20'=>'40', 'A1'=>'41', 'A2'=>'42', 'A3'=>'43', 'A4'=>'44', 'A5'=>'45', 'A6'=>'46', 'A7'=>'47',
             'A8'=>'48', 'A9'=>'49', '5B'=>'4A', '2E'=>'4B', '3C'=>'4C', '28'=>'4D', '2B'=>'4E', '7C'=>'4F',
             '26'=>'50', 'AA'=>'51', 'AB'=>'52', 'AC'=>'53', 'AD'=>'54', 'AE'=>'55', 'AF'=>'56', '1A'=>'57',
             'B0'=>'58', '1A'=>'59', '21'=>'5A', '5C'=>'5B', '2A'=>'5C', '29'=>'5D', '3B'=>'5E', '5E'=>'5F',
             '2D'=>'60', '2F'=>'61', '61'=>'62', '62'=>'63', '63'=>'64', '64'=>'65', '65'=>'66', '66'=>'67',
             '67'=>'68', '68'=>'69', '7C'=>'6A', '2C'=>'6B', '25'=>'6C', '5F'=>'6D', '3E'=>'6E', '3F'=>'6F',
             '5B'=>'70', '69'=>'71', '6A'=>'72', '6B'=>'73', '6C'=>'74', '6D'=>'75', '6E'=>'76', '6F'=>'77',
             '70'=>'78', '60'=>'79', '3A'=>'7A', '23'=>'7B', '40'=>'7C', '27'=>'7D', '3D'=>'7E', '22'=>'7F',
             '5D'=>'80', 'B1'=>'81', 'B2'=>'82', 'B3'=>'83', 'B4'=>'84', 'B5'=>'85', 'B6'=>'86', 'B7'=>'87',
             'B8'=>'88', 'B9'=>'89', 'BA'=>'8A', '71'=>'8B', 'BB'=>'8C', 'BC'=>'8D', 'BD'=>'8E', 'BE'=>'8F',
             'BF'=>'90', 'C0'=>'91', 'C1'=>'92', 'C2'=>'93', 'C3'=>'94', 'C4'=>'95', 'C5'=>'96', 'C6'=>'97',
             'C7'=>'98', 'C8'=>'99', 'C9'=>'9A', '72'=>'9B', '1A'=>'9C', 'CA'=>'9D', 'CB'=>'9E', 'CC'=>'9F',
             '7E'=>'A0', '7E'=>'A1', 'CD'=>'A2', 'CE'=>'A3', 'CF'=>'A4', 'D0'=>'A5', 'D1'=>'A6', 'D2'=>'A7',
             'D3'=>'A8', 'D4'=>'A9', 'D5'=>'AA', '73'=>'AB', 'D6'=>'AC', 'D7'=>'AD', 'D8'=>'AE', 'D9'=>'AF',
             '5E'=>'B0', '1A'=>'B1', '5C'=>'B2', '74'=>'B3', '75'=>'B4', '76'=>'B5', '77'=>'B6', '78'=>'B7',
             '79'=>'B8', '7A'=>'B9', 'DA'=>'BA', 'DB'=>'BB', 'DC'=>'BC', 'DD'=>'BD', 'DE'=>'BE', 'DF'=>'BF',
             '7B'=>'C0', '41'=>'C1', '42'=>'C2', '43'=>'C3', '44'=>'C4', '45'=>'C5', '46'=>'C6', '47'=>'C7',
             '48'=>'C8', '49'=>'C9', '1A'=>'CA', '1A'=>'CB', '1A'=>'CC', '1A'=>'CD', '1A'=>'CE', '1A'=>'CF',
             '7D'=>'D0', '4A'=>'D1', '4B'=>'D2', '4C'=>'D3', '4D'=>'D4', '4E'=>'D5', '4F'=>'D6', '50'=>'D7',
             '51'=>'D8', '52'=>'D9', '1A'=>'DA', '1A'=>'DB', '1A'=>'DC', '1A'=>'DD', '1A'=>'DE', '1A'=>'DF',
             '24'=>'E0', '1A'=>'E1', '53'=>'E2', '54'=>'E3', '55'=>'E4', '56'=>'E5', '57'=>'E6', '58'=>'E7',
             '59'=>'E8', '5A'=>'E9', '1A'=>'EA', '1A'=>'EB', '1A'=>'EC', '1A'=>'ED', '1A'=>'EE', '1A'=>'EF',
             '30'=>'F0', '31'=>'F1', '32'=>'F2', '33'=>'F3', '34'=>'F4', '35'=>'F5', '36'=>'F6', '37'=>'F7',
             '38'=>'F8', '39'=>'F9', '1A'=>'FA', '1A'=>'FB', '1A'=>'FC', '1A'=>'FD', '1A'=>'FE',
             );
           
            //  $file_path="storage/app/fixed_length_files/20-11-091_Text_File_1604902103.txt";
        if ($file_path!=null) {
            if (is_file($file_path)) {
                $file_data = file_get_contents($file_path);
            }else{
                $file_data =null;
                // echo "The file $file_path does not exist";
            }
        }
        // return $file_data;
        //  echo($file_data.PHP_EOL);
        $ascii_bin_data=null;
        if ($file_data !=null) {
            $hex_data = bin2hex($file_data);
            $ascii_data = '';
            $string_data = '';
            for ($i = 0; $i < strlen($hex_data); $i++) {
                $ebcdic_char = $hex_data[$i];
                $i++;
                $ebcdic_char .= $hex_data[$i];
                $ebcdic_char = strtoupper($ebcdic_char);
            
                $ascii_char = $ebcdic_map[$ebcdic_char];
                $ascii_data .= $ascii_char;
            }

            // $ascii_bin_data = hex2bin($ascii_data);
            $ascii_bin_data = pack('H*', $ascii_data);
        }
        return $ascii_bin_data;
    
}
}
