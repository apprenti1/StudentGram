#Requires -Version 5
class InteractiveMenuChooseMenuItem {
    [ValidateNotNullOrEmpty()][string]$Label
    [ValidateNotNullOrEmpty()][string]$Value
    [ValidateNotNullOrEmpty()][string]$Info

    InteractiveMenuChooseMenuItem([string]$label, [string]$value, [string]$info) {
        $this.Label = $label
        $this.Value = $value
        $this.Info = $Info
    }
}
class InteractiveMenuChooseMenu {
    [ValidateNotNullOrEmpty()][string]$Question
    [ValidateNotNullOrEmpty()][InteractiveMenuChooseMenuItem[]]$Options

    [ValidateNotNullOrEmpty()][ConsoleColor]$MenuInfoColor = [ConsoleColor]::DarkYellow
    [ValidateNotNullOrEmpty()][ConsoleColor]$QuestionColor = [ConsoleColor]::Magenta
    [ValidateNotNullOrEmpty()][ConsoleColor]$HelpColor = [ConsoleColor]::Cyan
    [ValidateNotNullOrEmpty()][ConsoleColor]$ErrorColor = [ConsoleColor]::DarkRed
    [ValidateNotNullOrEmpty()][ConsoleColor]$HighlightColor = [ConsoleColor]::DarkGreen
    [ValidateNotNullOrEmpty()][string]$OptionSeparator = "      "

    hidden [int]$CurrentIndex = 0
    hidden [InteractiveMenuChooseMenuItem]$SelectedOption = $null
    hidden [string]$Error = $null

    InteractiveMenuChooseMenu([string]$question, [InteractiveMenuChooseMenuItem[]]$options) {
        $this.Question = $question
        $this.Options = $options
    }

    [string] GetAnswer() {
        $shouldContinue = $true
        do {
            Clear-Host
            Invoke-Expression -Command $this.Question;
            <# Write-Host "$($this.Question)`n" -ForegroundColor $this.QuestionColor #>
            $this.Draw()
            $this.ShowCurrentItemInfo()
            $this.ShowErrors()
    
            $keyPress = [Console]::ReadKey("NoEcho,IncludeKeyDown")
            $shouldContinue = $this.ProcessKey($keyPress)
        } while ($shouldContinue)

        if ($null -eq $this.SelectedOption) {
            return $null
        }
        return $this.SelectedOption.Value
    }

    [void] SetOptions([hashtable]$options) {
        foreach ($option in $options.GetEnumerator()) {
            if ($null -eq $this.$($option.Key)) {
                Write-Host "Invalid option key: $($option.Key)"
            } else {
                $this.$($option.Key) = $option.Value
            }
        }
    }

    hidden Draw() {
        $defaultForegroundColor = (get-host).ui.rawui.ForegroundColor
        $defaultBackgroundColor = (get-host).ui.rawui.BackgroundColor

        $i = 0
        $this.options | ForEach-Object {
            $foregroundColor = $defaultForegroundColor
            $backgroundColor = $defaultBackgroundColor
            if ($i -eq $this.CurrentIndex) {
                $backgroundColor = $this.HighlightColor
            }
            Write-Host " $($_.Label) " -NoNewline -ForegroundColor $foregroundColor -BackgroundColor $backgroundColor
            Write-Host $this.OptionSeparator -NoNewline
            $i++
        }
        Write-Host
    }

    hidden ShowCurrentItemInfo() {
        $selectedItem = $this.Options[$this.CurrentIndex];
        if (-not [string]::IsNullOrEmpty($selectedItem.Info)) {
            Write-Host "`n$($selectedItem.Info)" -ForegroundColor $this.MenuInfoColor
        }
    }

    hidden [bool] ProcessKey($keyPress) {
        switch ($keyPress.Key) {
            $([ConsoleKey]::RightArrow) {
                $this.CurrentIndex++
                if ($this.CurrentIndex -ge $this.Options.Length) {
                    $this.CurrentIndex = $this.Options.Length -1;
                }
            }
            $([ConsoleKey]::LeftArrow) {
                $this.CurrentIndex--
                if ($this.CurrentIndex -lt 0) {
                    $this.CurrentIndex = 0;
                }
            }
            $([ConsoleKey]::Enter) {
                $this.StoreState()
                return $false
            }
            $([ConsoleKey]::Escape) {
                return $false
            }
            Default {
                $this.Error = "Unkown key pressed: $_. Use right, left, enter and escape."
            }
        }
        return $true
    }

    hidden StoreState() {
        $this.SelectedOption = $this.Options[$this.CurrentIndex]
    }

    hidden ShowUsage() {
        Write-Host "`nPress [h] for help." -ForegroundColor $this.HelpColor
    }

    hidden ShowErrors() {
        $bufferFill = "                                                                                                                                              "
        if (-not [string]::IsNullOrEmpty($this.Error)) {
            Write-Host "$($this.Error)$bufferFill" -ForegroundColor $this.ErrorColor
            $this.Error = $null
        } else {
            Write-Host $bufferFill
        }
    }
}
function Get-InteractiveChooseMenuOption() {
    param(
        [Parameter(Mandatory)][string]$Label,
        [Parameter(Mandatory)][string]$Value,
        [Parameter(Mandatory)][string]$Info
    )

    [InteractiveMenuChooseMenuItem]::new($Label, $Value, $Info)
}
function Get-InteractiveMenuChooseUserSelection {
    param(
        [Parameter(Mandatory)][string]$Question,
        [Parameter(Mandatory)][object[]]$Answers,
        [Parameter()][hashtable]$Options
    )

    $menu = [InteractiveMenuChooseMenu]::new($Question, $Answers)
    if ($null -ne $Options) {
        $menu.SetOptions($Options);
    }
    return $menu.GetAnswer()
}
Export-ModuleMember -Function Get-InteractiveMultiMenuOption,Get-InteractiveMenuUserSelection,Get-InteractiveChooseMenuOption,Get-InteractiveMenuChooseUserSelection