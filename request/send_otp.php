<?php
require_once '../model/OrangeSMS.php';
require_once '../model/Personnel.php';
session_start();


// Importation de la classe
$clientId = 'Xb6Wgzi9iCWFAJakdSNPCpMGBx9ixxF0';
$clientSecret = 'xOXZ4QTDf7bLfGk3';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $phone = $_POST['phone'];

    // Générer un OTP aléatoire
    $otp = rand(100000, 999999);

    $personnelObj = new Personnel();

    // Envoyer OTP via un service SMS (ex: Twilio, Nexmo, etc.)
    // Code d'envoi du SMS ici
    try {
        // Instanciation de la classe OrangeSMS
        $orangeSMS = new OrangeSMS($clientId, $clientSecret);

        // Format du numéro de téléphone sans ajouter de 'tel:' supplémentaire
        $recipientPhoneNumber = '+225' . $phone;
        $senderPhoneNumber = '+2250748367710';

        // Mettre à jour la base de données avec l'OTP et incrémenter nombre_demande_otp
        $updateDB = $personnelObj->mettreAJourOTP($phone, $otp);

        if (!$updateDB) {
            throw new Exception("Échec de la mise à jour de la base de données.");
        }



        // Envoi d'un SMS
        $message = 'Votre OTP est ' . $otp;
        $response = $orangeSMS->sendSMS('tel:' . $recipientPhoneNumber, 'tel:' . $senderPhoneNumber, $message);
        print_r($response);

        // Vérification du solde SMS
        $balance = $orangeSMS->getSMSBalance();
        print_r($balance);

        // Vérification de l'usage des SMS
        $usage = $orangeSMS->getSMSUsage();
        print_r($usage);

        $_SESSION['phone'] = $phone;
    } catch (Exception $e) {
        echo 'Erreur: ' . $e->getMessage();
    }

    // Stocker l'OTP dans la session pour la vérification ultérieure
    $_SESSION['otp'] = $otp;

    // Retourner une réponse JSON de succès
    echo json_encode(['status' => 'success', 'message' => 'OTP envoyé avec succès.']);
}
