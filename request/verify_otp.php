<?php
session_start(); // Démarrer la session

require_once '../model/Personnel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : null;

    // Récupérer l'OTP soumis (tableau) et vérifier si c'est un tableau
    $otpArray = isset($_POST['otp']) ? $_POST['otp'] : [];

    // Vérifiez que c'est bien un tableau avant d'utiliser implode
    if (is_array($otpArray) && count($otpArray) === 6) {
        $otp = implode('', $otpArray); // Combine les valeurs de l'OTP en une seule chaîne
    } else {
        // Si ce n'est pas un tableau ou n'a pas 6 chiffres, vous pouvez gérer l'erreur
        echo json_encode(['status' => 'error', 'message' => 'Erreur de soumission de l\'OTP.']);
        exit();
    }

    $personnelObj = new Personnel();

    try {
        // Vérifier si l'OTP est correct
        if ($personnelObj->verifierOTP($phone, $otp)) {
            // Si OTP valide, réinitialiser le compte de l'utilisateur
            $personnelObj->ReinitialiserCompteUtilisateur($phone);

            // Réponse JSON de succès
            echo json_encode(['status' => 'success', 'message' => 'Compte réinitialisé avec succès.']);

            // Redirection après succès (note : cela ne fonctionnera pas après un echo)
            unset($_SESSION['phone']);
            unset($_SESSION['otp']);
        } else {
            // OTP incorrect
            echo json_encode(['status' => 'error', 'message' => 'OTP incorrect.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenue : ' . $e->getMessage()]);
    }
}
