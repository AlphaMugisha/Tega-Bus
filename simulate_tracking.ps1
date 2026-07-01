# Simulation script for TransitEco Live Tracking
$apiUrl = "http://127.0.0.1:8000/api/v1/driver/location"
$scheduleId = "550e8400-e29b-41d4-a716-446655440000"

# Coordinates from Kigali to Gisenyi (approximate path)
$points = @(
    @{lat = -1.9441; lng = 30.0619},
    @{lat = -1.9200; lng = 30.0000},
    @{lat = -1.8800; lng = 29.9000},
    @{lat = -1.8400; lng = 29.8000},
    @{lat = -1.8000; lng = 29.7000},
    @{lat = -1.7600; lng = 29.6000},
    @{lat = -1.7200; lng = 29.5000},
    @{lat = -1.6800; lng = 29.4000},
    @{lat = -1.6400; lng = 29.3000},
    @{lat = -1.7024; lng = 29.2553}
)

Write-Host "Starting TransitEco Tracking Simulation..." -ForegroundColor Green
Write-Host "Target: $apiUrl"
Write-Host "Schedule ID: $scheduleId"

foreach ($point in $points) {
    $body = @{
        schedule_id = $scheduleId
        latitude = $point.lat
        longitude = $point.lng
        speed = Get-Random -Minimum 40 -Maximum 85
        heading = 280
    } | ConvertTo-Json

    $headers = @{
        "Accept" = "application/json"
    }

    try {
        $response = Invoke-RestMethod -Uri $apiUrl -Method Post -Body $body -ContentType "application/json" -Headers $headers
        Write-Host "Updated Location: $($point.lat), $($point.lng) | Speed: $($body | ConvertFrom-Json | Select -ExpandProperty speed) km/h" -ForegroundColor Cyan
    } catch {
        $errorMessage = $_.Exception.Response.GetResponseStream()
        $reader = New-Object System.IO.StreamReader($errorMessage)
        $responseBody = $reader.ReadToEnd()
        Write-Host "Failed to update location: $responseBody" -ForegroundColor Red
    }

    Start-Sleep -Seconds 5
}

Write-Host "Simulation Completed." -ForegroundColor Green
