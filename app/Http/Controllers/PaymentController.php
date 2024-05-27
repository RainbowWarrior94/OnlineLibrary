<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrow;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function pay($id)
    {
        $borrow = Borrow::find($id);  
        $startDate = Carbon::parse($borrow->borrowed_at);
        $endDate = Carbon::now();
        $bookingPeriod = $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y');
        $pricePerDay = 1;
        $bookingDays = ($startDate->diffInDays($endDate)) + 1;
        $totalPrice = $bookingDays * $pricePerDay;
        $book = $borrow->book;
        $author = $book->author;
        return view('pay', compact('totalPrice', 'book', 'author', 'bookingPeriod', 'borrow'));
    }

    public function processPayment(Request $request)
{
    $bookingPeriod = $request->bookingPeriod;
    $totalPrice = $request->totalPrice;
    $cardNumber = $request->cardNumber;
    $expiryDate = $request->expiryDate;
    $cvv = $request->cvv;
    $bookingId = $request->bookingId;
    if (Auth::check()) {
    
      $userId = Auth::id();
      $user = User::find($userId);

      $username = $user->name;

      Borrow::where('id', $bookingId)
          ->where('user_id', $userId)
          ->update(['returned_at' => now()]);

      $bookingId = intval($bookingId);
      $borrow = Borrow::find($bookingId);
        
        $book = Book::find($borrow->book_id);

        $book = Book::find($borrow->book_id);
        $isbn = $book->isbn;

      Payment::create([
          'borrow_id' => $bookingId,
          'payment_amount' => $totalPrice,
          'payment_date' => Carbon::now()
      ]);

    // $api_key = 'c3df53a126b746028009e8057ee3b884';
    $api_key = '3ecf6b1c5cf34bd797a5f4c57951a1cf';

    $edi_interchange_json = '{

      "SegmentDelimiter": "~",
      "DataElementDelimiter": "*",
      "ISA": {
        "AuthorizationInformationQualifier_1": "00",
        "AuthorizationInformation_2": "          ",
        "SecurityInformationQualifier_3": "00",
        "SecurityInformation_4": "          ",
        "SenderIDQualifier_5": "ZZ",
        "InterchangeSenderID_6": "BIBLIOC        ",
        "ReceiverIDQualifier_7": "ZZ",
        "InterchangeReceiverID_8": "'. str_pad($userId, 15, '0', STR_PAD_LEFT) .'",
        "InterchangeDate_9": "'. date('dmy').'",
        "InterchangeTime_10": "'. date('Hi').'",
        "InterchangeControlStandardsIdentifier_11": "U",
        "InterchangeControlVersionNumber_12": "00204",
        "InterchangeControlNumber_13": "000000263",
        "AcknowledgementRequested_14": "1",
        "UsageIndicator_15": "T",
        "ComponentElementSeparator_16": ">"
      },
      "Groups": [
        {
          "GS": {
            "CodeIdentifyingInformationType_1": "IN",
            "SenderIDCode_2": "BIBLIOC",
            "ReceiverIDCode_3": "'. str_pad($userId, 15, '0', STR_PAD_LEFT) .'",
            "Date_4": "'. date('dmy').'",
            "Time_5": "'. date('Hi').'",
            "GroupControlNumber_6": "000000001",
            "TransactionTypeCode_7": "X",
            "VersionAndRelease_8": "004010"
          },
          "Transactions": [
            {
              "ST": {
                "TransactionSetIdentifierCode_01": "810",
                "TransactionSetControlNumber_02": "'. rand(1000, 9999) .'"
              },
              "BIG": {
                "Date_01": "'. date('Ymd') .'",
                "InvoiceNumber_02": "'. $bookingId .'",
                "Date_03": "'. date('Ymd') .'",
                "PurchaseOrderNumber_04": "'. $bookingId .'",
                "ReleaseNumber_05": "1001"
              },
              "N1Loop": [
                {
                  "N1": {
                    "EntityIdentifierCode_01": "ST",
                    "Name_02": "BIBLIOCONNECT",
                    "IdentificationCodeQualifier_03": "9",
                    "IdentificationCode_04": "6496816264"
                  },
                  "N3": [
                    {
                      "AddressInformation_01": "STERLINGA 26"
                    }
                  ],
                  "N4": {
                    "CityName_01": "LODZ",
                    "StateorProvinceCode_02": "42",
                    "PostalCode_03": "90212"
                  }
                }
              ],
              "ITD": [
                {
                  "TermsTypeCode_01": "05",
                  "TermsBasisDateCode_02": "1",
                  "TermsNetDays_07": "0",
                  "PaymentMethodCode_14": "E"
                }
              ],
              "IT1Loop": [
                {
                  "IT1": {
                    "AssignedIdentification_01": "1",
                    "QuantityInvoiced_02": "1",
                    "UnitorBasisforMeasurementCode_03": "EA",
                    "UnitPrice_04": "1",
                    "ProductServiceIDQualifier_06": "IB",
                    "ProductServiceID_07": "'. $isbn .'"
                  }
                }
              ],
              "TDS": {
                "Amount_01": "'. $totalPrice .'00"
              },
              "CTT": {
                "NumberofLineItems_01": "1"
              },
              "SE": {
                "NumberofIncludedSegments_01": "10",
                "TransactionSetControlNumber_02": "0001"
              },
              "Model": "EdiNation.X12.ASC.004010"
            }
          ],
          "GETrailers": [
            {
              "NumberOfIncludedSets_1": "1",
              "GroupControlNumber_2": "000000001"
            }
          ]
        }
      ],
      "IEATrailers": [
        {
          "NumberOfIncludedGroups_1": "1",
          "InterchangeControlNumber_2": "000000263"
        }
      ],
      "Result": {
        "LastIndex": 14,
        "Details": [],
        "Status": "success"
      }
           
    }';

    $url = 'https://api.edination.com/v2/x12/write';
    $headers = array(
        'Content-Type: application/json',
        'Ocp-Apim-Subscription-Key: ' . $api_key
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $edi_interchange_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        echo 'Ошибка cURL: ' . curl_error($ch);
    } else {
        $file_name =  $bookingId .'_'. $username . '_EDI_File.x12';
        $file_path = 'edi_files/'. $file_name;
        file_put_contents($file_path, $response);
        $file_url = url($file_path);
    }

    curl_close($ch);
    return view('success-payment', compact('file_url'));
    } 
  }
}


// public function downloadFile($id)
//     {
//         $borrow = Borrow::find($id);
//         if (!$borrow) {
//             return response()->json(['error' => 'Файл не найден'], 404);
//         }

//         $file_path = public_path('edi_files/edi_file.x12');

//         if (file_exists($file_path)) {
//             return response()->download($file_path);
//         } else {
//             return response()->json(['error' => 'Файл не найден'], 404);
//         }
//     }





  // // Получение данных из запроса
    // $bookingPeriod = $request->bookingPeriod;
    // $totalPrice = $request->totalPrice;
    // $cardNumber = $request->cardNumber;
    // $expiryDate = $request->expiryDate;
    // $cvv = $request->cvv;
    // $bookingId = $request->bookingId; // Получение идентификатора бронирования
    
    // // Проверка, аутентифицирован ли пользователь
    // if (Auth::check()) {
    //     // Получение идентификатора аутентифицированного пользователя
    //     $userId = Auth::id();

    //     // Ваша логика проверки данных платежа

    //     // Обновление записи в таблице borrows
        
        
    //     Borrow::where('id', $bookingId)
    //         ->where('user_id', $userId)
    //         ->update(['returned_at' => now()]);

    //         $bookingId = intval($bookingId);

    //     // Создание записи в таблице payments
    //     Payment::create([
    //         'borrow_id' => $bookingId,
    //         'payment_amount' => $totalPrice,
    //         'payment_date' => Carbon::now()
    //     ]);
    
    //     // Возвращение редиректа на страницу успешной оплаты
        
    //     return view('success-payment');
    // } else {
    //     // Если пользователь не аутентифицирован, выполните соответствующие действия, например, перенаправление на страницу входа
    // }