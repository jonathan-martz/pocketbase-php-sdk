package main

import (
    "bytes"
    "encoding/json"
    "flag"
    "fmt"
    "log"
    "net/smtp"
    "os/exec"
    "time"
)

func main() {
    now := time.Now()

    // Define flags
    smtpPass := flag.String("password", "", "SMTP password")
    sender := flag.String("sender", "", "SMTP sender")
    smtpUser := flag.String("user", "", "SMTP username")

    // Parse the flags
    flag.Parse()

    // Check if required flags are provided
    if *smtpPass == "" {
        log.Fatal("Error: Password is required. Use -password flag.")
    }
    if *smtpUser == "" {
        log.Fatal("Error: SMTP username is required. Use -user flag.")
    }
    if *sender == "" {
        log.Fatal("Error: Sender email is required. Use -sender flag.")
    }

    // Format the date as YYYY-MM-DD
    currentDate := now.Format("01.02.2006")

    recipient := "support@jonathan-martz.de"
    subject := "PHPUnit - PocketBase PHP SDK - " + currentDate
    smtpHost := "smtps.udag.de" // Replace with your SMTP host
    smtpPort := "587"           // Replace with your SMTP port

    // Run PHPUnit with combined stdout and stderr
    cmd := exec.Command("php", "vendor/bin/phpunit")
    var output bytes.Buffer
    cmd.Stdout = &output
    cmd.Stderr = &output

    if err := cmd.Run(); err != nil {
        log.Printf("PHPUnit command failed: %v", err)
    }

    // Check if output is empty
    if output.Len() == 0 {
        log.Fatal("PHPUnit output is empty. Please check your configuration.")
    }

    // Parse the JSON output
    var result map[string]interface{}
    if err := json.Unmarshal(output.Bytes(), &result); err != nil {
        log.Fatalf("Failed to parse JSON output: %v", err)
    }

    // Extract the 'counts' field
    counts, ok := result["counts"].(map[string]interface{})
    if !ok {
        log.Fatal("Counts field not found in PHPUnit output.")
    }

    // Check if 'fails' is greater than 0
    failed, ok := counts["failed"].(float64)
    if !ok {
        log.Fatal("'fails' field not found in counts.")
    }

    if failed > 0 {
        // Serialize the counts field to JSON for the email body
        countsJSON, err := json.MarshalIndent(counts, "", "  ")
        if err != nil {
            log.Fatalf("Failed to serialize counts to JSON: %v", err)
        }

        // Prepare the email body
        emailBody := fmt.Sprintf("Subject: %s\r\n\r\n%s", subject, string(countsJSON))

        // Connect to the SMTP server
        auth := smtp.PlainAuth("", *smtpUser, *smtpPass, smtpHost)
        err = smtp.SendMail(
            smtpHost+":"+smtpPort,
            auth,
            *sender,
            []string{recipient},
            []byte(emailBody),
        )
        if err != nil {
            log.Fatalf("Failed to send email: %v", err)
        }

        log.Println("Test results sent to", recipient)
    } else {
        log.Println("No failures detected. No email sent.")
    }
}