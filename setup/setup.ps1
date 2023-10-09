$OutputEncoding = [System.Text.Encoding]::UTF8

function verrifinstall() {
    for ($i = 0; $i -lt $global:valid.Count; $i++) {
        $global:valid[$i]['installed'] = 0
    }
    foreach ($path in ($Env:PATH -split ';')) {
        if($path -ne ""){
            for ($i = 0; $i -lt $global:valid.Count; $i++) {
                if (Test-Path (Join-Path -Path $path -ChildPath $global:valid[$i]['install'])) {
                    $global:valid[$i]['installed'] = 1;
                    $global:valid[$i]['version'] = ((( (Invoke-Expression -Command $global:valid[$i]['command'])  | Select-String -Pattern $global:valid[$i]['split']) -split ' ')[$global:valid[$i]['splitNumber']]) -replace '\x1B\[[0-9;]*[A-Za-z]' ; 
                }
                if ($global:max -lt ($global:valid[$i]['version']+$global:valid[$i]['name']).Length) {
                    $global:max = ($global:valid[$i]['version']+$global:valid[$i]['name']).Length;
                }
            }
        }
    }
}
function lineVersionTable($istop, $size, $isfail, $name, $version) {
    if ($istop -eq 1) {
        Write-Host ("+"+("-"*($size+17))+"+") -ForegroundColor Blue;
    } else {
        Write-Host "|` " -NoNewline -ForegroundColor Blue;
        Write-Host ($name+"` :` ") -NoNewline -ForegroundColor White;
        if ($isfail -ne 1) {
            Write-Host "failed` ` ` " -NoNewline -ForegroundColor Red;
        } else {
            Write-Host "Installed" -NoNewline -ForegroundColor Green;
        }
        Write-Host "` :` " -NoNewline -ForegroundColor White;
        Write-Host $version -NoNewline -ForegroundColor Yellow;
        Write-Host "$("` "*($size-($version.Length+$name.Length)))` |" -ForegroundColor Blue;
    }
}
$global:lineVersionTable = ${function:lineVersionTable};
function installSymfony(){
    try {
        $findrelease = Invoke-RestMethod -Uri "https://github.com/symfony-cli/symfony-cli/releases" -Method Get
        $findrelease = "https://github.com/symfony-cli/symfony-cli/releases/download/"+((($findrelease -split '/symfony-cli/symfony-cli/releases/tag/')[1]) -split '"')[0]+"/symfony-cli_windows_amd64.zip";
        Write-Host "`n`n`n`n`nsrc:` " -ForegroundColor White -NoNewline
        Write-Host $findrelease -foregroundcolor Blue
        Write-Host "destination:` " -ForegroundColor White -NoNewline 
        Write-Host "c:\Users\$($env:USERNAME)\appdata\roaming\symfony" -ForegroundColor Blue
        Invoke-WebRequest -Uri $findrelease -OutFile "c:/Users/$env:USERNAME/symfony.zip"
        Expand-Archive -LiteralPath "c:\Users\$($env:USERNAME)\symfony.zip" -DestinationPath "c:\Users\$($env:USERNAME)\appdata\roaming\symfony" -Force
        [System.Environment]::SetEnvironmentVariable("PATH", "c:\Users\$($env:USERNAME)\appdata\roaming\symfony;$([System.Environment]::GetEnvironmentVariable("PATH", [System.EnvironmentVariableTarget]::User))", [System.EnvironmentVariableTarget]::User)
        $PROFILE.CurrentUserCurrentHost
        $Env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine")
        $Env:Path = [System.Environment]::GetEnvironmentVariable("Path","User")
        Remove-Item -Path "c:/Users/$env:USERNAME/symfony.zip"
    }
    catch {
        Write-Host "failed` ` ` Error:`n$($_.Exception.Message))" -ForegroundColor Red;
        exit
    }
}
function installComposer(){
    Write-Host "`n`n`n`n`nsrc:` " -ForegroundColor White -NoNewline
    Write-Host "https://getcomposer.org/Composer-Setup.exe" -foregroundcolor Blue
    Write-Host "destination:` " -ForegroundColor White -NoNewline 
    Write-Host "Custom with installer" -ForegroundColor Blue
    Invoke-WebRequest -Uri "https://getcomposer.org/Composer-Setup.exe" -OutFile "c:/Users/$env:USERNAME/Composer.exe"
    Clear-Host
    Write-Host "`n`nPass after the end of Composerinstall !" -ForegroundColor Blue
    Start-Process "c:/Users/$env:USERNAME/Composer.exe"
    Pause
    Clear-Host
    $PROFILE.CurrentUserCurrentHost
    $Env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine")
    $Env:Path = [System.Environment]::GetEnvironmentVariable("Path","User")
    Remove-Item -Path "c:/Users/$env:USERNAME/Composer.exe"
}
function installPHP(){

    $findrelease = (Invoke-RestMethod -Uri "https://www.php.net/downloads" -Method Get)
    $findrelease = "https://windows.php.net/$(((($findrelease -split 'https://windows.php.net/')[1]) -split '"')[0])"
    $findrelease = (Invoke-RestMethod -Uri $findrelease -Method Get)    
    $findrelease = $findrelease.Substring($findrelease.IndexOf("main-column"))
    $findrelease = $findrelease.Substring($findrelease.IndexOf("corners-bottom"))
    $findrelease = $findrelease.Substring($findrelease.IndexOf("x64 Thread Safe"))
    $findrelease = $findrelease.Substring($findrelease.IndexOf("/downloads/releases/"))
    $findrelease = "https://windows.php.net$($findrelease.Substring(0, $findrelease.IndexOf('"')))"
    Write-Host "`n`n`n`n`nsrc:` " -ForegroundColor White -NoNewline
    Write-Host $findrelease -foregroundcolor Blue
    Write-Host "destination:` " -ForegroundColor White -NoNewline 
    Write-Host "c:\Users\$($env:USERNAME)\appdata\roaming\php" -ForegroundColor Blue
    Invoke-WebRequest -Uri $findrelease -OutFile "c:/Users/$env:USERNAME/php.zip"
    if(Test-Path -Path "c:\Users\$($env:USERNAME)\appdata\roaming\php"){
        Remove-Item -Path "c:\Users\$($env:USERNAME)\appdata\roaming\php" -recurse -force
    }
    Expand-Archive -LiteralPath "c:\Users\$($env:USERNAME)\php.zip" -DestinationPath "c:\Users\$($env:USERNAME)\appdata\roaming\php" -Force
    [System.Environment]::SetEnvironmentVariable("PATH", "c:\Users\$($env:USERNAME)\appdata\roaming\php;$([System.Environment]::GetEnvironmentVariable("PATH", [System.EnvironmentVariableTarget]::User))", [System.EnvironmentVariableTarget]::User)
    $PROFILE.CurrentUserCurrentHost
    $Env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine")
    $Env:Path = [System.Environment]::GetEnvironmentVariable("Path","User")
    Remove-Item -Path "c:/Users/$env:USERNAME/php.zip"
}

$global:valid = @(
    @{"installed" = 0; "version" = "` ---` "; "name" = "PHP` ` ` ` ` "; "command" = "PHP -v"; "splitNumber" = 1; "split" = "PHP"; "install" = "php.exe"; "installer" = "installPHP"; "nbPkBefore" = 1},
    @{"installed" = 0; "version" = "` ---` "; "name" = "Symfony` "; "command" = "symfony -v"; "splitNumber" = 3; "split" = "Symfony"; "install" = "symfony.exe"; "installer" = "installSymfony"; "nbPkBefore" = 1},
    @{"installed" = 0; "version" = "` ---` "; "name" = "Composer"; "command" = "composer -v"; "splitNumber" = 2; "split" = "Composer"; "install" = "composer.bat"; "installer" = "installComposer"; "nbPkBefore" = 2}
)

$global:max = 0
verrifinstall;
do{
    $allgood = 1;

    foreach ($item in $global:valid) {
        if ($item['installed'] -eq 0) {
            Import-Module .\setup\InteractiveMenu.psd1
            $answers = @(
                Get-InteractiveChooseMenuOption `
                    -Label "Yes" `
                    -Value 1 `
                    -Info "(esc) to escape | Launch the installation of $($item['name'])"
                Get-InteractiveChooseMenuOption `
                    -Label "No" `
                    -Value 0 `
                    -Info "(esc) to escape | Refuse automatic installation"
            )
            
            $options = @{
                MenuInfoColor = [ConsoleColor]::DarkYellow;
                QuestionColor = [ConsoleColor]::DarkGreen;
                HelpColor = [ConsoleColor]::Cyan;
                ErrorColor = [ConsoleColor]::DarkRed;
                HighlightColor = [ConsoleColor]::DarkGreen;
                OptionSeparator = " | ";
            }
            
            $answer = Get-InteractiveMenuChooseUserSelection -Question "
            Write-Host `"`n`";
            lineVersionTable 1  `$global:max;
            foreach (`$item in `$global:valid) {
                lineVersionTable 0 `$global:max `$item['installed'] `$item['name'] `$item['version']
            }
            lineVersionTable 1  `$global:max;
            Write-Host `"`nIt seems that $($item['name']) is not installed. `nDo you want to do the automatic installation?`n`" -ForegroundColor DarkGreen
            " -Answers $answers -Options $options
            Remove-Module InteractiveMenu
            if ($answer -eq 1) {
                Clear-Host
                Write-Host "$--------------------Install $($item['name'].Replace(' ', ''))--------------------$" -ForegroundColor Blue
                Invoke-Expression -Command $item['installer'];
                verrifinstall;
            } elseif ($answer -ne 0) {
                exit
            }
        }
        if ($item['installed'] -eq 0) {
            $allgood = 0;
        }
    }
    if ($allgood -eq 1) {
        Import-Module .\setup\InteractiveMenu.psd1
        $answers = @()
        $answers += Get-InteractiveChooseMenuOption `
            -Label "Continue without" `
            -Value "-" `
            -Info "(esc) to escape | pass to the project launch)"
        foreach ($item in $valid) {
            $answers += `
                Get-InteractiveChooseMenuOption `
                -Label (($item['name']).Replace(" ", "")) `
                -Value "@(`"$($item['command'])`", `"$($item['name'])`", $($item['nbPkBefore']))" `
                -Info "(esc) to escape | Delete $($item['name'])"
        }
        $options = @{
            MenuInfoColor = [ConsoleColor]::DarkYellow;
            QuestionColor = [ConsoleColor]::DarkGreen;
            HelpColor = [ConsoleColor]::Cyan;
            ErrorColor = [ConsoleColor]::DarkRed;
            HighlightColor = [ConsoleColor]::DarkGreen;
            OptionSeparator = " | ";
        }
        
        $answer = Get-InteractiveMenuChooseUserSelection -Question "
        Write-Host `"`n`";
        lineVersionTable 1  `$global:max;
        foreach (`$item in `$global:valid) {
            lineVersionTable 0 `$global:max `$item['installed'] `$item['name'] `$item['version']
        }
        lineVersionTable 1  `$global:max;
        Write-Host `"`nDo you want to (delete for upgrade/upgrade) something for upgrade?`n`" -ForegroundColor DarkGreen
        " -Answers $answers -Options $options
        Remove-Module InteractiveMenu
        if (($answer -ne "") -and ($answer -ne "-")) {
            $element = Invoke-Expression -command $answer
            $path = (Resolve-Path -Path (((Get-Command (($element[0]).Substring(0, ($element[0]).indexof(" ")))).Source)+("\.."*$element[2]))).Path
            Import-Module .\setup\InteractiveMenu.psd1
            $answers = @(
            Get-InteractiveChooseMenuOption `
                -Label "Delete to upgrade" `
                -Value 1 `
                -Info "(esc) to escape | delete $($element['name'])"
            Get-InteractiveChooseMenuOption `
                -Label "Upgrade" `
                -Value 0 `
                -Info "(esc) to escape | Upgrade $($element['name']) with install at other path"
        )
            $options = @{
                MenuInfoColor = [ConsoleColor]::DarkYellow;
                QuestionColor = [ConsoleColor]::DarkGreen;
                HelpColor = [ConsoleColor]::Cyan;
                ErrorColor = [ConsoleColor]::DarkRed;
                HighlightColor = [ConsoleColor]::DarkGreen;
                OptionSeparator = " | ";
            }
            
            $answer = Get-InteractiveMenuChooseUserSelection -Question "
            Write-Host `"`n`";
            
            Write-Host `"`nDo you want to delete $($element['name']) for upgrade?`nor just upgrade/install $($element['name']) at other path?`n(Favorite to upgrade if you want it)`n`" -ForegroundColor DarkGreen
            " -Answers $answers -Options $options
            Remove-Module InteractiveMenu
            if ($answer -eq 1) {
                $finalpath = ""
                foreach ($actualpath in ($([System.Environment]::GetEnvironmentVariable("PATH", [System.EnvironmentVariableTarget]::User)) -split ';')) {
                    if($actualpath -ne ""){
                        if ($actualpath -ne $path) {
                            $finalpath += ($actualpath+";")
                        }
                    }
                }
                [System.Environment]::SetEnvironmentVariable("PATH", $finalpath, [System.EnvironmentVariableTarget]::User)
                $PROFILE.CurrentUserCurrentHost
                $Env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine")
                $Env:Path = [System.Environment]::GetEnvironmentVariable("Path","User")
                Remove-Item -Path $path -recurse -force
                pause
            }
            elseif ($answer -eq 0) {
                Clear-Host
                Write-Host "$--------------------Install $($element['name'].Replace(' ', ''))--------------------$" -ForegroundColor Blue
                Invoke-Expression -Command $item['installer'];

            }
            verrifinstall;
            $allgood = 0;
        }
    }
} while ($allgood -eq 0)


Clear-Host
Write-Host "`n";
lineVersionTable 1  $global:max;
foreach ($item in $global:valid) {
    lineVersionTable 0 $global:max $item['installed'] $item['name'] $item['version']
}
lineVersionTable 1  $global:max;
$bdd = $(((Get-Content -path .env) -join ";"))
$bdd = $bdd.Substring($bdd.IndexOf(";DATABASE_URL=")+15)
$bdd = $bdd.Substring(0, $bdd.IndexOf('"'))


Write-Host "Be sure to make the following sql server available:" -ForegroundColor Blue
Write-Host $bdd -ForegroundColor Green
Pause

echo "composer require symfony runtime"
composer require symfony runtime
echo "php bin/console doctrine:database:create"
php bin/console doctrine:database:create
echo "php bin/console doctrine:migrations:version --add --all"
php bin/console doctrine:migrations:version --add --all
echo "php bin/console doctrine:migrations:migrate"
php bin/console doctrine:migrations:migrate
echo "symfony server:ca:install"
symfony server:ca:install
echo "symfony serve"
symfony serve