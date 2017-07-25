<?php
include_once dirname(__FILE__) . '/engine/engine.php';
$apiversion = "0.66";
header('Access-Control-Allow-Origin: *');
?>
<!doctype html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title ng-bind-template="{{webPageTitle}}">Travian Kingdoms</title>

        <meta name="HandheldFriendly" content="true" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />

        <!--<meta name="viewport" content="target-densitydpi=device-dpi"/>-->
        <!--<meta name="viewport" content="width=device-width"/>-->

        <script>
            var config = {
                "gameWorld": "test",
                "direction": "ltr",
                "version": "0.66.9",
                "urlGameRules": "http:\/\/www.kingdoms.com\/X\/rules",
                "liveUrl": "\/api\/",
                "SERVER_ENV": "live",
                "node": {
                    "host": "<?php echo $domain; ?>",
                    "port": 8081,
                    "resource": "chat"
                },
                "portal": "<?php echo $lobby_url; ?>",
                "portalLogout": "",
                "veryShortTimeFormat": "dd.MM. | HH:mm",
                "mellon": {
                    "url": "<?php echo $mellon_url; ?>",
                    "applicationId": "travian-ks",
                    "applicationCountryId": "en",
                    "applicationInstanceId": "test",
                    "applicationLanguageId": "en_US",
                    "cookieDomain": ".<?php echo $domain; ?>",
                    "checkSession": false
                },
                "paymentShopDisabled": false
            };
            config['live'] = true;
            config['useTemplateCache'] = true;
            config['templates'] = "<?php echo $cdn_url . $apiversion; ?>/templates.php?h=f0a52b0f5c732fabeb39a519cec38941";
            config['direction'] = "ltr";
            config['mellon']['styles'] = 'https://cdn.traviantools.net/startpage/live/css/ltr/mellonDialogue.css?h=6a3fd7150227b2bb2132320d9122417f';

            window['zargetData'] = {
                "gold": 0,
                "hasPayed": 0,
                "prestigeLvl": 5,
                "language": "en"
            };
        </script>

        <!-- Zarget -->
        <script type='text/javascript'>
            (function () {
                window.zarget = true;
                var protocol = ('https:' == document.location.protocol ? 'https:' :
                        'http:');
                var scriptTag = document.createElement('script');
                scriptTag.type = 'text/javascript';
                scriptTag.async = true;
                scriptTag.src = protocol + '//cdn.zarget.com/103640/170906.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(scriptTag, s);
            })();

            function zargetTimeout() {
                window.zarget = false;
            }
            window.zargetTimer = setTimeout(zargetTimeout, 3000);
        </script>

        <script type="text/javascript">
            var FileLoader = function (a, d, c, b) {
                this.debug = b || false;
                this.scriptType = {
                    css: "text/css",
                    js: "text/javascript"
                };
                this.scripts = [];
                this.listenerLoaded = d;
                this.listenerAdded = a;
                this.listenerError = c;
                this.listenerDone = null
            };
            var Script = function (c, d, a) {
                this.asyncFiles = ["css"];
                this.fileType = a;
                this.asyncLoading = false;
                this.src = c;
                this.callback = d;
                if ((typeof this.fileType === "undefined" || this.fileType == "")) {
                    this.fileType = b(c)
                }
                if (this.asyncFiles.indexOf(this.fileType) >= 0) {
                    this.asyncLoading = true
                }

                function b(f) {
                    var e = f.slice((Math.max(0, f.lastIndexOf(".")) || 0) + 1);
                    if (e.indexOf("?") >= 0) {
                        e = e.slice(0, e.indexOf("?"))
                    }
                    return e
                }
            };
            FileLoader.prototype = {
                logger: function (a) {
                    if (this.debug) {
                        console.log("[FileLoader]", a)
                    }
                },
                addScript: function (c, d, b) {
                    var a = new Script(c, d, b);
                    if (a.asyncLoading) {
                        this.scripts.unshift(a)
                    } else {
                        this.scripts.push(a)
                    }
                    this.logger("ADDED: " + a.src);
                    if (typeof this.listenerAdded == "function") {
                        this.listenerAdded.call(this, a.src)
                    }
                },
                load: function (a) {
                    if (typeof a === "function") {
                        this.listenerDone = a
                    }
                    this.createScriptElement(this.scripts.shift())
                },
                ieLoadBugFix: function (b, a) {
                    if (b.readyState == "loaded" || b.readyState == "complete") {
                        a()
                    } else {
                        setTimeout(function () {
                            this.ieLoadBugFix(b, a)
                        }, 100)
                    }
                },
                scriptEventHandler: function (b, a) {
                    if (b.type == "error") {
                        if (typeof this.listenerError == "function") {
                            this.listenerError.call(this, a.src)
                        }
                        this.logger("ERROR: " + a.src + " could not be loaded. Stopping ...")
                    } else {
                        this.logger("LOADED: " + a.src);
                        if (typeof this.listenerLoaded == "function") {
                            this.listenerLoaded.call(this, a.src)
                        }
                        if (typeof a.callback == "function") {
                            a.callback.call(this)
                        }
                        if (this.scripts.length == 0) {
                            if (typeof this.listenerDone == "function") {
                                this.listenerDone.call(this)
                            }
                        } else {
                            if (!a.asyncLoading) {
                                this.createScriptElement(this.scripts.shift());
                                this.logger("SCRIPTS LEFT: " + this.scripts.length)
                            }
                        }
                    }
                },
                createScriptElement: function (b) {
                    this.logger("CREATE ELEMENT: " + b.src);
                    var a = this;
                    var d = null;
                    if (b.fileType == "css") {
                        d = document.createElement("link");
                        d.rel = "stylesheet";
                        d.href = b.src
                    } else {
                        d = document.createElement("script");
                        d.src = b.src
                    }
                    d.type = this.scriptType[b.fileType == "php" ? "js" : b.fileType];
                    d.async = false;
                    if (typeof d.addEventListener !== "undefined") {
                        d.addEventListener("load", function (e) {
                            a.scriptEventHandler(e, b)
                        }, false);
                        d.addEventListener("error", function (e) {
                            a.scriptEventHandler(e, b)
                        }, false)
                    } else {
                        d.onreadystatechange = function (e) {
                            d.onreadystatechange = null;
                            this.ieLoadBugFix(d, function () {
                                a.scriptLoaded(e, b)
                            })
                        }
                    }
                    var c = document.getElementsByTagName("head")[0];
                    c.appendChild(d);
                    this.logger("LOAD SCRIPT ASYNC", b.asyncLoading);
                    if (b.asyncLoading && this.scripts.length > 0) {
                        this.createScriptElement(this.scripts.shift());
                        this.logger("SCRIPTS LEFT: " + this.scripts.length)
                    }
                }
            };

            function LoadingScreenManager() {
                var h = [];
                var a = 0;
                var c = 0;
                var b = {
                    transparent: null,
                    fullVisible: null,
                    swordFullOffset: 0
                };
                var k = null;
                var e = null;
                var g = 0;
                var i = null;
                var l = 17;
                this.showUnload = function () {
                    if (e == null) {
                        e = document.querySelector(".loadingScreen")
                    }
                    if (e != null) {
                        e.style.display = "block";
                        e.className += " unload"
                    }
                };
                this.registerStep = function (n) {
                    h.push(n);
                    a++
                };
                this.achieveStep = function (n) {
                    var o = h.indexOf(n);
                    if (o >= 0) {
                        h.splice(o, 1);
                        c++;
                        m()
                    }
                };

                function j() {
                    if (b.fullVisible == null) {
                        b.fullVisible = document.querySelector(".loadingScreen .loadingBar .progressBar .sword.fullVisible");
                        if (b.fullVisible != null) {
                            b.swordFullOffset = b.fullVisible.offsetWidth
                        }
                    }
                    if (b.transparent == null) {
                        b.transparent = document.querySelector(".loadingScreen .loadingBar .progressBar .sword.transparent")
                    }
                    if (k == null) {
                        k = document.querySelector(".loadingScreen .loadingBar .loadingPercent .loadingTextNumber")
                    }
                    if (b.transparent != null && b.fullVisible != null && k != null) {
                        var n = ((g * (b.transparent.offsetWidth - b.swordFullOffset)) / 100) + b.swordFullOffset;
                        b.fullVisible.style.width = n + "px";
                        document.querySelector(".loadingScreen .loadingBar .loadingPercent .loadingTextNumber").textContent = g + ""
                    }
                }

                function f() {
                    var n = document.querySelector(".loadingScreen");
                    n.style.display = "none"
                }

                function d() {
                    var n = Date.now() - loadingStartTime;
                    var o = Date.now();
                    i = setInterval(function () {
                        if (g <= 100) {
                            j();
                            var p = Date.now() - o;
                            g = Math.round(50 + ((p / n) * 50))
                        } else {
                            g = 100;
                            j();
                            f();
                            clearInterval(i)
                        }
                    }, l)
                }

                function m() {
                    var n = Math.round((((c) * 50) / a));
                    if (g < n) {
                        g = n;
                        j()
                    }
                    if (i != null) {
                        clearInterval(i);
                        i = null
                    }
                    if (c < a) {
                        i = setInterval(function () {
                            if (g < 50) {
                                g++;
                                j()
                            } else {
                                m();
                                clearInterval(i)
                            }
                        }, 500)
                    } else {
                        d()
                    }
                }
                this.onFileLoadingError = function (q) {
                    var o = null;

                    function p() {
                        var r = "";
                        var s = {
                            controller: "error",
                            action: "logLoadingError",
                            params: {
                                playerId: Travian.Globals.playerId,
                                error: q
                            }
                        };
                        if (typeof (config.SERVER_ENV) == "undefined") {
                            config.SERVER_ENV = "devPHP"
                        }
                        if (config.SERVER_ENV === "devPHP") {
                            r = config.devPHPUrl
                        } else {
                            if (config.SERVER_ENV === "live") {
                                r = config.liveUrl
                            }
                        }
                        var u = new Date().getTime();
                        var t = new XMLHttpRequest();
                        t.open("POST", encodeURI(r + "?c=error&a=logLoadingError&t=" + u));
                        t.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                        t.send(JSON.stringify(s))
                    }

                    function n() {
                        if (o == null) {
                            o = document.querySelector(".loadingScreen .centerArea .loadingText .action.loadingGame");
                            n()
                        } else {
                            var r = "";
                            if (q.indexOf("?") >= 0) {
                                r = q.substring(q.lastIndexOf("/") + 1, q.indexOf("?"))
                            } else {
                                r = q.substring(q.lastIndexOf("/") + 1)
                            }
                            o.className += " errorMessage";
                            o.textContent = 'An error has occurred whilst loading "' + r + '". Please reload the page. If the problem persists, please contact our game support.'
                        }
                    }
                    if (typeof config != "undefined") {
                        p()
                    }
                    n()
                }
            }
            ;
            var loadingStartTime = Date.now();
            GlobalLoadingScreenManager = new LoadingScreenManager();
            GlobalLoadingScreenManager.registerStep('socket_connect');
            GlobalLoadingScreenManager.registerStep('get_all');
            var fileLoader = new FileLoader(GlobalLoadingScreenManager.registerStep, GlobalLoadingScreenManager.achieveStep, GlobalLoadingScreenManager.onFileLoadingError, true);
            var tutorialTexts = {};
            // we will be pushing init functions here which depend on Travian.Config
            // and run those after config is there
            var onConfigLoaded = [];
            var $ = null
            fileLoader.addScript('<?php echo $cdn_url . $apiversion; ?>/js/game.js?h=c909ce9ac11528fd7f28e68a4740ff43', function () {
                //put all game design related config here
                Travian.Globals = {};
                Travian.Config = {};
                Travian.Language = 'en';
                Travian.Globals['sessionId'] = '';
                Travian.Globals['playerId'] = '';
                Travian.Config = {
                    "features": {
                        "rubble": true,
                        "prestige": true,
                        "achievements": true,
                        "cardGame": true,
                        "dailyQuests": true,
                        "inviteFriend": true,
                        "femaleHero": true,
                        "finishNowAll": false,
                        "chatAutoBan": false,
                        "treasuresOnMap": true
                    },
                    "specialRules": {
                        "cropDiet": false
                    },
                    "goldConfig": {
                        "plusAccount": 10,
                        "plusAccountDuration": 432000,
                        "plusAccountWholeRoundAvailable": true,
                        "masterBuilder": 1,
                        "instantConstruction": 2,
                        "instantConstructionReduced": 1,
                        "instantConstructionTimeReduced": 7200,
                        "instantConstructionTimeFree": 300,
                        "instantConstructionAll": 3,
                        "npcTrading": 5,
                        "collectTributeInstant": 1,
                        "maxTradeRecurrences": 2,
                        "productionBonus": 20,
                        "productionBonusDuration": 432000,
                        "productionBonusWholeRoundAvailable": true,
                        "cropProductionBonus": 10,
                        "cropProductionBonusDuration": 432000,
                        "cropProductionWholeRoundAvailable": true,
                        "starterPackage": 60,
                        "starterPackageDuration": 604800,
                        "extraBuildingMasterSlot1": 50,
                        "extraBuildingMasterSlot2": 75,
                        "extraBuildingMasterSlot3": 100,
                        "treasureResourcesInstant": 3,
                        "traderArriveInstantlyMin": 2,
                        "traderArriveInstantlyMid": 2,
                        "traderArriveInstantlyMax": 2,
                        "traderArriveInstantlyMinMinutes": 10,
                        "traderArriveInstantlyMidMinutes": 60,
                        "traderSlot1": 50,
                        "traderSlot2": 25,
                        "cardgameSingle": 5,
                        "cardgame4of5": 20,
                        "rewardSecondVillage": 20,
                        "rewardThirdVillage": 30,
                        "rewardFirstPayment": 50,
                        "reward1000PrestigeReached": 100
                    },
                    "merchantSpeed": {
                        "0": 0,
                        "1": 16,
                        "2": 12,
                        "3": 24,
                        "5": 0
                    },
                    "treasuryTransformTime": 43200,
                    "tournamentSquareMinDistance": 20,
                    "oasisConfig": {
                        "oasisBonus": 25,
                        "embassyLevels": {
                            "1": 1,
                            "2": 10,
                            "3": 20
                        },
                        "usableArea": 3
                    },
                    "worldWonderMinAttackUnits": 50,
                    "townConfig": {
                        "acceptanceMaxForTown": 200,
                        "townAdditionalPopulation": 500,
                        "townAdditionalCultureProduction": 200,
                        "townAdditionalCultureProductionMain": 500,
                        "townRequirementForPopulation": 500
                    },
                    "heroConfig": {
                        "heroHealthRegenPerDay": 15,
                        "MaxWaterbucketsPerDay": 1,
                        "MaxOintmentsPerDay": 100,
                        "MaxAdventurePointsCardPerDay": 1,
                        "MaxResourceChestsPerDay": 1,
                        "MaxCropChestsPerDay": 1,
                        "MaxArtworksPerDay": 1,
                        "HeroBaseStrengthRomans": 400,
                        "HeroBaseStrengthOthers": 320
                    },
                    "TG_SPEED": 1,//<?php echo $engine->server->speed_world;?>,
                    "TG_TROOP_SPEED": <?php echo $engine->server->speed_unit;?>,
                    "worldSize": 800,
                    "farmListDefaultVillageLimit": 10,
                    "farmListVillageLimit": 100,
                    "farmListLimit": 100,
                    "farmListNameMaxLength": 15,
                    "worldStart": 1487595600,
                    "troopConfig": {
                        "1": {
                            "id": 1,
                            "nr": 1,
                            "tribe": 1,
                            "costs": {
                                "1": 75,
                                "2": 50,
                                "3": 100,
                                "4": 0
                            },
                            "time": 1600,
                            "supply": 1,
                            "speed": 6,
                            "carry": 50,
                            "attack": 40,
                            "defence": 35,
                            "defenceCavalry": 50,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 19,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 550,
                                    "2": 300,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 6600
                            }
                        },
                        "2": {
                            "id": 2,
                            "nr": 2,
                            "tribe": 1,
                            "costs": {
                                "1": 80,
                                "2": 100,
                                "3": 160,
                                "4": 0
                            },
                            "time": 1760,
                            "supply": 1,
                            "speed": 5,
                            "carry": 20,
                            "attack": 30,
                            "defence": 65,
                            "defenceCavalry": 35,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 13,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 580,
                                    "2": 500,
                                    "3": 1480,
                                    "4": 0
                                },
                                "time": 7080
                            }
                        },
                        "3": {
                            "id": 3,
                            "nr": 3,
                            "tribe": 1,
                            "costs": {
                                "1": 100,
                                "2": 110,
                                "3": 140,
                                "4": 0
                            },
                            "time": 1920,
                            "supply": 1,
                            "speed": 7,
                            "carry": 50,
                            "attack": 70,
                            "defence": 40,
                            "defenceCavalry": 25,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 13,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 540,
                                    "3": 1320,
                                    "4": 0
                                },
                                "time": 7560
                            }
                        },
                        "4": {
                            "id": 4,
                            "nr": 4,
                            "tribe": 1,
                            "costs": {
                                "1": 100,
                                "2": 140,
                                "3": 10,
                                "4": 0
                            },
                            "time": 1360,
                            "supply": 2,
                            "speed": 16,
                            "carry": 0,
                            "attack": 0,
                            "defence": 20,
                            "defenceCavalry": 10,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 660,
                                    "3": 280,
                                    "4": 0
                                },
                                "time": 5880
                            }
                        },
                        "5": {
                            "id": 5,
                            "nr": 5,
                            "tribe": 1,
                            "costs": {
                                "1": 350,
                                "2": 260,
                                "3": 180,
                                "4": 0
                            },
                            "time": 2640,
                            "supply": 3,
                            "speed": 14,
                            "carry": 100,
                            "attack": 120,
                            "defence": 65,
                            "defenceCavalry": 50,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 2200,
                                    "2": 1140,
                                    "3": 1640,
                                    "4": 0
                                },
                                "time": 9720
                            }
                        },
                        "6": {
                            "id": 6,
                            "nr": 6,
                            "tribe": 1,
                            "costs": {
                                "1": 280,
                                "2": 340,
                                "3": 600,
                                "4": 0
                            },
                            "time": 3520,
                            "supply": 4,
                            "speed": 10,
                            "carry": 70,
                            "attack": 180,
                            "defence": 80,
                            "defenceCavalry": 105,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 1780,
                                    "2": 1460,
                                    "3": 5000,
                                    "4": 0
                                },
                                "time": 12360
                            }
                        },
                        "7": {
                            "id": 7,
                            "nr": 7,
                            "tribe": 1,
                            "costs": {
                                "1": 700,
                                "2": 180,
                                "3": 400,
                                "4": 0
                            },
                            "time": 4600,
                            "supply": 3,
                            "speed": 4,
                            "carry": 0,
                            "attack": 60,
                            "defence": 30,
                            "defenceCavalry": 75,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 21,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 4300,
                                    "2": 820,
                                    "3": 3400,
                                    "4": 0
                                },
                                "time": 15600
                            }
                        },
                        "8": {
                            "id": 8,
                            "nr": 8,
                            "tribe": 1,
                            "costs": {
                                "1": 690,
                                "2": 1000,
                                "3": 400,
                                "4": 0
                            },
                            "time": 9000,
                            "supply": 6,
                            "speed": 3,
                            "carry": 0,
                            "attack": 75,
                            "defence": 60,
                            "defenceCavalry": 10,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 15,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 21,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 4240,
                                    "2": 4100,
                                    "3": 3400,
                                    "4": 0
                                },
                                "time": 28800
                            }
                        },
                        "9": {
                            "id": 9,
                            "nr": 9,
                            "tribe": 1,
                            "costs": {
                                "1": 30750,
                                "2": 27200,
                                "3": 45000,
                                "4": 0
                            },
                            "time": 90700,
                            "supply": 5,
                            "speed": 4,
                            "carry": 0,
                            "attack": 50,
                            "defence": 40,
                            "defenceCavalry": 30,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 20,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 16,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 15880,
                                    "2": 13800,
                                    "3": 36400,
                                    "4": 0
                                },
                                "time": 24475
                            }
                        },
                        "10": {
                            "id": 10,
                            "nr": 10,
                            "tribe": 1,
                            "costs": {
                                "1": 3500,
                                "2": 3000,
                                "3": 4500,
                                "4": 0
                            },
                            "time": 26900,
                            "supply": 1,
                            "speed": 5,
                            "carry": 3000,
                            "attack": 0,
                            "defence": 80,
                            "defenceCavalry": 80,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 21100,
                                    "2": 12100,
                                    "3": 36200,
                                    "4": 0
                                },
                                "time": 82500
                            }
                        },
                        "11": {
                            "id": 11,
                            "nr": 1,
                            "tribe": 2,
                            "costs": {
                                "1": 85,
                                "2": 65,
                                "3": 30,
                                "4": 0
                            },
                            "time": 720,
                            "supply": 1,
                            "speed": 7,
                            "carry": 60,
                            "attack": 40,
                            "defence": 20,
                            "defenceCavalry": 5,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 19,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 610,
                                    "2": 360,
                                    "3": 440,
                                    "4": 0
                                },
                                "time": 3960
                            }
                        },
                        "12": {
                            "id": 12,
                            "nr": 2,
                            "tribe": 2,
                            "costs": {
                                "1": 125,
                                "2": 50,
                                "3": 65,
                                "4": 0
                            },
                            "time": 1120,
                            "supply": 1,
                            "speed": 7,
                            "carry": 40,
                            "attack": 10,
                            "defence": 35,
                            "defenceCavalry": 60,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 19,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 850,
                                    "2": 300,
                                    "3": 720,
                                    "4": 0
                                },
                                "time": 5160
                            }
                        },
                        "13": {
                            "id": 13,
                            "nr": 3,
                            "tribe": 2,
                            "costs": {
                                "1": 80,
                                "2": 65,
                                "3": 130,
                                "4": 0
                            },
                            "time": 1200,
                            "supply": 1,
                            "speed": 6,
                            "carry": 50,
                            "attack": 60,
                            "defence": 30,
                            "defenceCavalry": 30,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 13,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 580,
                                    "2": 360,
                                    "3": 1240,
                                    "4": 0
                                },
                                "time": 5400
                            }
                        },
                        "14": {
                            "id": 14,
                            "nr": 4,
                            "tribe": 2,
                            "costs": {
                                "1": 140,
                                "2": 80,
                                "3": 30,
                                "4": 0
                            },
                            "time": 1120,
                            "supply": 1,
                            "speed": 9,
                            "carry": 0,
                            "attack": 0,
                            "defence": 10,
                            "defenceCavalry": 5,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 940,
                                    "2": 420,
                                    "3": 440,
                                    "4": 0
                                },
                                "time": 5160
                            }
                        },
                        "15": {
                            "id": 15,
                            "nr": 5,
                            "tribe": 2,
                            "costs": {
                                "1": 330,
                                "2": 170,
                                "3": 200,
                                "4": 0
                            },
                            "time": 2400,
                            "supply": 2,
                            "speed": 10,
                            "carry": 110,
                            "attack": 55,
                            "defence": 100,
                            "defenceCavalry": 40,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 2080,
                                    "2": 780,
                                    "3": 1800,
                                    "4": 0
                                },
                                "time": 9000
                            }
                        },
                        "16": {
                            "id": 16,
                            "nr": 6,
                            "tribe": 2,
                            "costs": {
                                "1": 280,
                                "2": 320,
                                "3": 260,
                                "4": 0
                            },
                            "time": 2960,
                            "supply": 3,
                            "speed": 9,
                            "carry": 80,
                            "attack": 150,
                            "defence": 50,
                            "defenceCavalry": 75,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 15,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 1780,
                                    "2": 1380,
                                    "3": 2280,
                                    "4": 0
                                },
                                "time": 10680
                            }
                        },
                        "17": {
                            "id": 17,
                            "nr": 7,
                            "tribe": 2,
                            "costs": {
                                "1": 800,
                                "2": 150,
                                "3": 250,
                                "4": 0
                            },
                            "time": 4200,
                            "supply": 3,
                            "speed": 4,
                            "carry": 0,
                            "attack": 65,
                            "defence": 30,
                            "defenceCavalry": 80,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 21,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 4900,
                                    "2": 700,
                                    "3": 2200,
                                    "4": 0
                                },
                                "time": 14400
                            }
                        },
                        "18": {
                            "id": 18,
                            "nr": 8,
                            "tribe": 2,
                            "costs": {
                                "1": 660,
                                "2": 900,
                                "3": 370,
                                "4": 0
                            },
                            "time": 9000,
                            "supply": 6,
                            "speed": 3,
                            "carry": 0,
                            "attack": 50,
                            "defence": 60,
                            "defenceCavalry": 10,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 15,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 21,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 4060,
                                    "2": 3700,
                                    "3": 3160,
                                    "4": 0
                                },
                                "time": 28800
                            }
                        },
                        "19": {
                            "id": 19,
                            "nr": 9,
                            "tribe": 2,
                            "costs": {
                                "1": 35500,
                                "2": 26600,
                                "3": 25000,
                                "4": 0
                            },
                            "time": 70500,
                            "supply": 4,
                            "speed": 4,
                            "carry": 0,
                            "attack": 40,
                            "defence": 60,
                            "defenceCavalry": 40,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 20,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 16,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 18250,
                                    "2": 13500,
                                    "3": 20400,
                                    "4": 0
                                },
                                "time": 19425
                            }
                        },
                        "20": {
                            "id": 20,
                            "nr": 10,
                            "tribe": 2,
                            "costs": {
                                "1": 4000,
                                "2": 3500,
                                "3": 3200,
                                "4": 0
                            },
                            "time": 31000,
                            "supply": 1,
                            "speed": 5,
                            "carry": 3000,
                            "attack": 10,
                            "defence": 80,
                            "defenceCavalry": 80,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 24100,
                                    "2": 14100,
                                    "3": 25800,
                                    "4": 0
                                },
                                "time": 94800
                            }
                        },
                        "21": {
                            "id": 21,
                            "nr": 1,
                            "tribe": 3,
                            "costs": {
                                "1": 85,
                                "2": 100,
                                "3": 50,
                                "4": 0
                            },
                            "time": 1040,
                            "supply": 1,
                            "speed": 7,
                            "carry": 35,
                            "attack": 15,
                            "defence": 40,
                            "defenceCavalry": 50,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 19,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 610,
                                    "2": 500,
                                    "3": 600,
                                    "4": 0
                                },
                                "time": 4920
                            }
                        },
                        "22": {
                            "id": 22,
                            "nr": 2,
                            "tribe": 3,
                            "costs": {
                                "1": 95,
                                "2": 60,
                                "3": 140,
                                "4": 0
                            },
                            "time": 1440,
                            "supply": 1,
                            "speed": 6,
                            "carry": 45,
                            "attack": 65,
                            "defence": 35,
                            "defenceCavalry": 20,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 13,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 670,
                                    "2": 340,
                                    "3": 1320,
                                    "4": 0
                                },
                                "time": 6120
                            }
                        },
                        "23": {
                            "id": 23,
                            "nr": 3,
                            "tribe": 3,
                            "costs": {
                                "1": 140,
                                "2": 110,
                                "3": 20,
                                "4": 0
                            },
                            "time": 1360,
                            "supply": 2,
                            "speed": 17,
                            "carry": 0,
                            "attack": 0,
                            "defence": 20,
                            "defenceCavalry": 10,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 940,
                                    "2": 540,
                                    "3": 360,
                                    "4": 0
                                },
                                "time": 5880
                            }
                        },
                        "24": {
                            "id": 24,
                            "nr": 4,
                            "tribe": 3,
                            "costs": {
                                "1": 200,
                                "2": 280,
                                "3": 130,
                                "4": 0
                            },
                            "time": 2480,
                            "supply": 2,
                            "speed": 19,
                            "carry": 75,
                            "attack": 90,
                            "defence": 25,
                            "defenceCavalry": 40,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 1300,
                                    "2": 1220,
                                    "3": 1240,
                                    "4": 0
                                },
                                "time": 9240
                            }
                        },
                        "25": {
                            "id": 25,
                            "nr": 5,
                            "tribe": 3,
                            "costs": {
                                "1": 300,
                                "2": 270,
                                "3": 190,
                                "4": 0
                            },
                            "time": 2560,
                            "supply": 2,
                            "speed": 16,
                            "carry": 35,
                            "attack": 45,
                            "defence": 115,
                            "defenceCavalry": 55,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 1900,
                                    "2": 1180,
                                    "3": 1720,
                                    "4": 0
                                },
                                "time": 9480
                            }
                        },
                        "26": {
                            "id": 26,
                            "nr": 6,
                            "tribe": 3,
                            "costs": {
                                "1": 300,
                                "2": 380,
                                "3": 440,
                                "4": 0
                            },
                            "time": 3120,
                            "supply": 3,
                            "speed": 13,
                            "carry": 65,
                            "attack": 140,
                            "defence": 60,
                            "defenceCavalry": 165,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 15,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 1900,
                                    "2": 1620,
                                    "3": 3720,
                                    "4": 0
                                },
                                "time": 11160
                            }
                        },
                        "27": {
                            "id": 27,
                            "nr": 7,
                            "tribe": 3,
                            "costs": {
                                "1": 750,
                                "2": 370,
                                "3": 220,
                                "4": 0
                            },
                            "time": 5000,
                            "supply": 3,
                            "speed": 4,
                            "carry": 0,
                            "attack": 50,
                            "defence": 30,
                            "defenceCavalry": 105,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 21,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 4600,
                                    "2": 1580,
                                    "3": 1960,
                                    "4": 0
                                },
                                "time": 16800
                            }
                        },
                        "28": {
                            "id": 28,
                            "nr": 8,
                            "tribe": 3,
                            "costs": {
                                "1": 590,
                                "2": 1200,
                                "3": 400,
                                "4": 0
                            },
                            "time": 9000,
                            "supply": 6,
                            "speed": 3,
                            "carry": 0,
                            "attack": 70,
                            "defence": 45,
                            "defenceCavalry": 10,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 15,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 21,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 3640,
                                    "2": 4900,
                                    "3": 3400,
                                    "4": 0
                                },
                                "time": 28800
                            }
                        },
                        "29": {
                            "id": 29,
                            "nr": 9,
                            "tribe": 3,
                            "costs": {
                                "1": 30750,
                                "2": 45400,
                                "3": 31000,
                                "4": 0
                            },
                            "time": 90700,
                            "supply": 4,
                            "speed": 5,
                            "carry": 0,
                            "attack": 40,
                            "defence": 50,
                            "defenceCavalry": 50,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 20,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 16,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "research": {
                                "costs": {
                                    "1": 15880,
                                    "2": 22900,
                                    "3": 25200,
                                    "4": 0
                                },
                                "time": 24475
                            }
                        },
                        "30": {
                            "id": 30,
                            "nr": 10,
                            "tribe": 3,
                            "costs": {
                                "1": 3000,
                                "2": 4000,
                                "3": 3000,
                                "4": 0
                            },
                            "time": 22700,
                            "supply": 1,
                            "speed": 5,
                            "carry": 3000,
                            "attack": 0,
                            "defence": 80,
                            "defenceCavalry": 80,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 18100,
                                    "2": 16100,
                                    "3": 24200,
                                    "4": 0
                                },
                                "time": 69900
                            }
                        },
                        "31": {
                            "id": 31,
                            "nr": 1,
                            "tribe": 4,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 100
                            },
                            "time": 300,
                            "supply": 1,
                            "speed": 20,
                            "carry": 0,
                            "attack": 10,
                            "defence": 25,
                            "defenceCavalry": 20,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 2700
                            }
                        },
                        "32": {
                            "id": 32,
                            "nr": 2,
                            "tribe": 4,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 100
                            },
                            "time": 360,
                            "supply": 1,
                            "speed": 20,
                            "carry": 0,
                            "attack": 20,
                            "defence": 35,
                            "defenceCavalry": 40,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 2880
                            }
                        },
                        "33": {
                            "id": 33,
                            "nr": 3,
                            "tribe": 4,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 100
                            },
                            "time": 420,
                            "supply": 1,
                            "speed": 20,
                            "carry": 0,
                            "attack": 60,
                            "defence": 40,
                            "defenceCavalry": 60,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 3060
                            }
                        },
                        "34": {
                            "id": 34,
                            "nr": 4,
                            "tribe": 4,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 100
                            },
                            "time": 480,
                            "supply": 1,
                            "speed": 20,
                            "carry": 0,
                            "attack": 80,
                            "defence": 66,
                            "defenceCavalry": 50,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 3240
                            }
                        },
                        "35": {
                            "id": 35,
                            "nr": 5,
                            "tribe": 4,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 100
                            },
                            "time": 540,
                            "supply": 2,
                            "speed": 20,
                            "carry": 0,
                            "attack": 50,
                            "defence": 70,
                            "defenceCavalry": 33,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 3420
                            }
                        },
                        "36": {
                            "id": 36,
                            "nr": 6,
                            "tribe": 4,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 100
                            },
                            "time": 600,
                            "supply": 2,
                            "speed": 20,
                            "carry": 0,
                            "attack": 100,
                            "defence": 80,
                            "defenceCavalry": 70,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 3600
                            }
                        },
                        "37": {
                            "id": 37,
                            "nr": 7,
                            "tribe": 4,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 100
                            },
                            "time": 660,
                            "supply": 3,
                            "speed": 20,
                            "carry": 0,
                            "attack": 250,
                            "defence": 140,
                            "defenceCavalry": 200,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 3780
                            }
                        },
                        "38": {
                            "id": 38,
                            "nr": 8,
                            "tribe": 4,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 100
                            },
                            "time": 720,
                            "supply": 3,
                            "speed": 20,
                            "carry": 0,
                            "attack": 450,
                            "defence": 380,
                            "defenceCavalry": 240,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 3960
                            }
                        },
                        "39": {
                            "id": 39,
                            "nr": 9,
                            "tribe": 4,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 100
                            },
                            "time": 780,
                            "supply": 3,
                            "speed": 20,
                            "carry": 0,
                            "attack": 200,
                            "defence": 170,
                            "defenceCavalry": 250,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 550,
                                    "2": 250,
                                    "3": 480,
                                    "4": 0
                                },
                                "time": 1995
                            }
                        },
                        "40": {
                            "id": 40,
                            "nr": 10,
                            "tribe": 4,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 100
                            },
                            "time": 840,
                            "supply": 5,
                            "speed": 20,
                            "carry": 0,
                            "attack": 600,
                            "defence": 440,
                            "defenceCavalry": 520,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 4320
                            }
                        },
                        "41": {
                            "id": 41,
                            "nr": 1,
                            "tribe": 5,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 50
                            },
                            "time": 240,
                            "supply": 1,
                            "speed": 6,
                            "carry": 10,
                            "attack": 20,
                            "defence": 35,
                            "defenceCavalry": 50,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 2520
                            }
                        },
                        "42": {
                            "id": 42,
                            "nr": 2,
                            "tribe": 5,
                            "costs": {
                                "1": 100,
                                "2": 100,
                                "3": 100,
                                "4": 50
                            },
                            "time": 240,
                            "supply": 1,
                            "speed": 7,
                            "carry": 10,
                            "attack": 65,
                            "defence": 30,
                            "defenceCavalry": 10,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 700,
                                    "2": 500,
                                    "3": 1000,
                                    "4": 0
                                },
                                "time": 2520
                            }
                        },
                        "43": {
                            "id": 43,
                            "nr": 3,
                            "tribe": 5,
                            "costs": {
                                "1": 150,
                                "2": 150,
                                "3": 150,
                                "4": 150
                            },
                            "time": 360,
                            "supply": 1,
                            "speed": 6,
                            "carry": 10,
                            "attack": 100,
                            "defence": 90,
                            "defenceCavalry": 75,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 1000,
                                    "2": 700,
                                    "3": 1400,
                                    "4": 0
                                },
                                "time": 2880
                            }
                        },
                        "44": {
                            "id": 44,
                            "nr": 4,
                            "tribe": 5,
                            "costs": {
                                "1": 50,
                                "2": 50,
                                "3": 50,
                                "4": 75
                            },
                            "time": 120,
                            "supply": 1,
                            "speed": 25,
                            "carry": 10,
                            "attack": 0,
                            "defence": 10,
                            "defenceCavalry": 10,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 400,
                                    "2": 300,
                                    "3": 600,
                                    "4": 0
                                },
                                "time": 2160
                            }
                        },
                        "45": {
                            "id": 45,
                            "nr": 5,
                            "tribe": 5,
                            "costs": {
                                "1": 300,
                                "2": 150,
                                "3": 150,
                                "4": 100
                            },
                            "time": 480,
                            "supply": 2,
                            "speed": 14,
                            "carry": 10,
                            "attack": 155,
                            "defence": 80,
                            "defenceCavalry": 50,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 1900,
                                    "2": 700,
                                    "3": 1400,
                                    "4": 0
                                },
                                "time": 3240
                            }
                        },
                        "46": {
                            "id": 46,
                            "nr": 6,
                            "tribe": 5,
                            "costs": {
                                "1": 250,
                                "2": 250,
                                "3": 400,
                                "4": 150
                            },
                            "time": 600,
                            "supply": 3,
                            "speed": 12,
                            "carry": 10,
                            "attack": 170,
                            "defence": 140,
                            "defenceCavalry": 80,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 1600,
                                    "2": 1100,
                                    "3": 3400,
                                    "4": 0
                                },
                                "time": 3600
                            }
                        },
                        "47": {
                            "id": 47,
                            "nr": 7,
                            "tribe": 5,
                            "costs": {
                                "1": 400,
                                "2": 300,
                                "3": 300,
                                "4": 400
                            },
                            "time": 720,
                            "supply": 4,
                            "speed": 5,
                            "carry": 10,
                            "attack": 250,
                            "defence": 120,
                            "defenceCavalry": 150,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 2500,
                                    "2": 1300,
                                    "3": 2600,
                                    "4": 0
                                },
                                "time": 3960
                            }
                        },
                        "48": {
                            "id": 48,
                            "nr": 8,
                            "tribe": 5,
                            "costs": {
                                "1": 200,
                                "2": 200,
                                "3": 200,
                                "4": 100
                            },
                            "time": 600,
                            "supply": 5,
                            "speed": 3,
                            "carry": 10,
                            "attack": 60,
                            "defence": 45,
                            "defenceCavalry": 10,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 1300,
                                    "2": 900,
                                    "3": 1800,
                                    "4": 0
                                },
                                "time": 3600
                            }
                        },
                        "49": {
                            "id": 49,
                            "nr": 9,
                            "tribe": 5,
                            "costs": {
                                "1": 1000,
                                "2": 1000,
                                "3": 1000,
                                "4": 1000
                            },
                            "time": 1800,
                            "supply": 1,
                            "speed": 5,
                            "carry": 10,
                            "attack": 80,
                            "defence": 50,
                            "defenceCavalry": 50,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 1000,
                                    "2": 700,
                                    "3": 1200,
                                    "4": 0
                                },
                                "time": 2250
                            }
                        },
                        "50": {
                            "id": 50,
                            "nr": 10,
                            "tribe": 5,
                            "costs": {
                                "1": 200,
                                "2": 200,
                                "3": 200,
                                "4": 200
                            },
                            "time": 1800,
                            "supply": 1,
                            "speed": 5,
                            "carry": 3000,
                            "attack": 30,
                            "defence": 40,
                            "defenceCavalry": 40,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 1300,
                                    "2": 900,
                                    "3": 1800,
                                    "4": 0
                                },
                                "time": 7200
                            }
                        },
                        "99": {
                            "id": 99,
                            "nr": 79,
                            "tribe": 3,
                            "costs": {
                                "1": 35,
                                "2": 30,
                                "3": 10,
                                "4": 20
                            },
                            "time": 600,
                            "supply": 0,
                            "speed": 0,
                            "carry": 0,
                            "attack": 0,
                            "defence": 0,
                            "defenceCavalry": 0,
                            "requirements": null,
                            "research": {
                                "costs": {
                                    "1": 520,
                                    "2": 220,
                                    "3": 410,
                                    "4": 0
                                },
                                "time": 1950
                            }
                        }
                    },
                    "recruitingBuildings": {
                        "19": true,
                        "29": true,
                        "20": true,
                        "30": true,
                        "21": true,
                        "25": true,
                        "26": true,
                        "36": true
                    },
                    "starvationCropMultiplier": 100,
                    "unitResearchStrengthMultiplier": 1.007,
                    "unitResearchSupplyMultiplier": 0.3,
                    "buildingConfig": {
                        "0": {
                            "buildingType": 0,
                            "costs": {
                                "1": 0,
                                "2": 0,
                                "3": 0,
                                "4": 0
                            },
                            "time": 30,
                            "tribeId": 0,
                            "category": 0,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "1": {
                            "buildingType": 1,
                            "costs": {
                                "1": 40,
                                "2": 100,
                                "3": 50,
                                "4": 60
                            },
                            "time": 25,
                            "tribeId": 0,
                            "category": 3,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "2": {
                            "buildingType": 2,
                            "costs": {
                                "1": 80,
                                "2": 40,
                                "3": 80,
                                "4": 50
                            },
                            "time": 20,
                            "tribeId": 0,
                            "category": 3,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "3": {
                            "buildingType": 3,
                            "costs": {
                                "1": 100,
                                "2": 80,
                                "3": 30,
                                "4": 60
                            },
                            "time": 30,
                            "tribeId": 0,
                            "category": 3,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "4": {
                            "buildingType": 4,
                            "costs": {
                                "1": 75,
                                "2": 90,
                                "3": 85,
                                "4": 0
                            },
                            "time": 20,
                            "tribeId": 0,
                            "category": 3,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "5": {
                            "buildingType": 5,
                            "costs": {
                                "1": 520,
                                "2": 380,
                                "3": 290,
                                "4": 90
                            },
                            "time": 480,
                            "tribeId": 0,
                            "category": 3,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 1,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "6": {
                            "buildingType": 6,
                            "costs": {
                                "1": 440,
                                "2": 480,
                                "3": 320,
                                "4": 50
                            },
                            "time": 480,
                            "tribeId": 0,
                            "category": 3,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 2,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "7": {
                            "buildingType": 7,
                            "costs": {
                                "1": 200,
                                "2": 450,
                                "3": 510,
                                "4": 120
                            },
                            "time": 480,
                            "tribeId": 0,
                            "category": 3,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 3,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "8": {
                            "buildingType": 8,
                            "costs": {
                                "1": 500,
                                "2": 440,
                                "3": 380,
                                "4": 1240
                            },
                            "time": 480,
                            "tribeId": 0,
                            "category": 3,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 4,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "9": {
                            "buildingType": 9,
                            "costs": {
                                "1": 1200,
                                "2": 1480,
                                "3": 870,
                                "4": 1600
                            },
                            "time": 780,
                            "tribeId": 0,
                            "category": 3,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 4,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 8,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "10": {
                            "buildingType": 10,
                            "costs": {
                                "1": 140,
                                "2": 180,
                                "3": 100,
                                "4": 0
                            },
                            "time": 35,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "11": {
                            "buildingType": 11,
                            "costs": {
                                "1": 80,
                                "2": 100,
                                "3": 70,
                                "4": 20
                            },
                            "time": 35,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "13": {
                            "buildingType": 13,
                            "costs": {
                                "1": 180,
                                "2": 250,
                                "3": 500,
                                "4": 160
                            },
                            "time": 40,
                            "tribeId": 0,
                            "category": 2,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "14": {
                            "buildingType": 14,
                            "costs": {
                                "1": 1750,
                                "2": 2250,
                                "3": 1530,
                                "4": 240
                            },
                            "time": 380,
                            "tribeId": 0,
                            "category": 2,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 16,
                                    "minLvl": 15,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "15": {
                            "buildingType": 15,
                            "costs": {
                                "1": 70,
                                "2": 40,
                                "3": 60,
                                "4": 20
                            },
                            "time": 30,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "16": {
                            "buildingType": 16,
                            "costs": {
                                "1": 110,
                                "2": 160,
                                "3": 90,
                                "4": 70
                            },
                            "time": 35,
                            "tribeId": 0,
                            "category": 2,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "17": {
                            "buildingType": 17,
                            "costs": {
                                "1": 80,
                                "2": 70,
                                "3": 120,
                                "4": 70
                            },
                            "time": 35,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                },
                                [{
                                        "type": 2,
                                        "buildingType": 10,
                                        "minLvl": 1,
                                        "op": 5,
                                        "valid": 0
                                    }, {
                                        "type": 2,
                                        "buildingType": 38,
                                        "minLvl": 1,
                                        "op": 5,
                                        "valid": 0
                                    }]
                            ],
                            "canFinishInstantly": true
                        },
                        "18": {
                            "buildingType": 18,
                            "costs": {
                                "1": 180,
                                "2": 130,
                                "3": 150,
                                "4": 80
                            },
                            "time": 35,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "19": {
                            "buildingType": 19,
                            "costs": {
                                "1": 210,
                                "2": 140,
                                "3": 260,
                                "4": 120
                            },
                            "time": 35,
                            "tribeId": 0,
                            "category": 2,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 16,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "20": {
                            "buildingType": 20,
                            "costs": {
                                "1": 260,
                                "2": 140,
                                "3": 220,
                                "4": 100
                            },
                            "time": 40,
                            "tribeId": 0,
                            "category": 2,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 13,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "21": {
                            "buildingType": 21,
                            "costs": {
                                "1": 460,
                                "2": 510,
                                "3": 600,
                                "4": 320
                            },
                            "time": 660,
                            "tribeId": 0,
                            "category": 2,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "22": {
                            "buildingType": 22,
                            "costs": {
                                "1": 220,
                                "2": 160,
                                "3": 90,
                                "4": 40
                            },
                            "time": 35,
                            "tribeId": 0,
                            "category": 2,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 19,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "23": {
                            "buildingType": 23,
                            "costs": {
                                "1": 40,
                                "2": 50,
                                "3": 30,
                                "4": 10
                            },
                            "time": 10,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "24": {
                            "buildingType": 24,
                            "costs": {
                                "1": 1250,
                                "2": 1110,
                                "3": 1260,
                                "4": 600
                            },
                            "time": 660,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 22,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "25": {
                            "buildingType": 25,
                            "costs": {
                                "1": 580,
                                "2": 460,
                                "3": 350,
                                "4": 180
                            },
                            "time": 1350,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 26,
                                    "minLvl": -1,
                                    "op": 0,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": false
                        },
                        "26": {
                            "buildingType": 26,
                            "costs": {
                                "1": 550,
                                "2": 800,
                                "3": 750,
                                "4": 250
                            },
                            "time": 3660,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 25,
                                    "minLvl": -1,
                                    "op": 0,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 18,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 1,
                                    "villageType": 4,
                                    "op": 1,
                                    "valid": 0
                                }],
                            "canFinishInstantly": false
                        },
                        "27": {
                            "buildingType": 27,
                            "costs": {
                                "1": 720,
                                "2": 685,
                                "3": 645,
                                "4": 250
                            },
                            "time": 2040,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": null,
                            "canFinishInstantly": false
                        },
                        "28": {
                            "buildingType": 28,
                            "costs": {
                                "1": 1400,
                                "2": 1330,
                                "3": 1200,
                                "4": 400
                            },
                            "time": 365,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 17,
                                    "minLvl": 20,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "29": {
                            "buildingType": 29,
                            "costs": {
                                "1": 630,
                                "2": 420,
                                "3": 780,
                                "4": 360
                            },
                            "time": 660,
                            "tribeId": 0,
                            "category": 2,
                            "requirements": [{
                                    "type": 1,
                                    "villageType": 2,
                                    "op": 0,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 19,
                                    "minLvl": 20,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "30": {
                            "buildingType": 30,
                            "costs": {
                                "1": 780,
                                "2": 420,
                                "3": 660,
                                "4": 300
                            },
                            "time": 660,
                            "tribeId": 0,
                            "category": 2,
                            "requirements": [{
                                    "type": 1,
                                    "villageType": 2,
                                    "op": 0,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 20,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "31": {
                            "buildingType": 31,
                            "costs": {
                                "1": 70,
                                "2": 90,
                                "3": 170,
                                "4": 70
                            },
                            "time": 35,
                            "tribeId": 1,
                            "category": 2,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "32": {
                            "buildingType": 32,
                            "costs": {
                                "1": 120,
                                "2": 200,
                                "3": 0,
                                "4": 80
                            },
                            "time": 35,
                            "tribeId": 2,
                            "category": 2,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "33": {
                            "buildingType": 33,
                            "costs": {
                                "1": 160,
                                "2": 100,
                                "3": 80,
                                "4": 60
                            },
                            "time": 35,
                            "tribeId": 3,
                            "category": 2,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "34": {
                            "buildingType": 34,
                            "costs": {
                                "1": 155,
                                "2": 130,
                                "3": 125,
                                "4": 70
                            },
                            "time": 35,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 1,
                                    "villageType": 1,
                                    "op": 0,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 5,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "35": {
                            "buildingType": 35,
                            "costs": {
                                "1": 1460,
                                "2": 930,
                                "3": 1250,
                                "4": 1740
                            },
                            "time": 690,
                            "tribeId": 2,
                            "category": 2,
                            "requirements": [{
                                    "type": 1,
                                    "villageType": 1,
                                    "op": 0,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 11,
                                    "minLvl": 20,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 16,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "36": {
                            "buildingType": 36,
                            "costs": {
                                "1": 80,
                                "2": 120,
                                "3": 70,
                                "4": 90
                            },
                            "time": 35,
                            "tribeId": 3,
                            "category": 2,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 16,
                                    "minLvl": 1,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "38": {
                            "buildingType": 38,
                            "costs": {
                                "1": 650,
                                "2": 800,
                                "3": 450,
                                "4": 200
                            },
                            "time": 325,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 1,
                                    "villageType": 4,
                                    "op": 0,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "39": {
                            "buildingType": 39,
                            "costs": {
                                "1": 400,
                                "2": 500,
                                "3": 350,
                                "4": 100
                            },
                            "time": 320,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 1,
                                    "villageType": 4,
                                    "op": 0,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "40": {
                            "buildingType": 40,
                            "costs": {
                                "1": 66700,
                                "2": 69050,
                                "3": 72200,
                                "4": 13200
                            },
                            "time": 3600,
                            "tribeId": 5,
                            "category": 1,
                            "requirements": [{
                                    "type": 1,
                                    "villageType": 4,
                                    "op": 0,
                                    "valid": 0
                                }],
                            "canFinishInstantly": false
                        },
                        "41": {
                            "buildingType": 41,
                            "costs": {
                                "1": 780,
                                "2": 420,
                                "3": 660,
                                "4": 540
                            },
                            "time": 660,
                            "tribeId": 1,
                            "category": 2,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 16,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 20,
                                    "minLvl": 20,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "42": {
                            "buildingType": 42,
                            "costs": {
                                "1": 740,
                                "2": 850,
                                "3": 960,
                                "4": 620
                            },
                            "time": 355,
                            "tribeId": 0,
                            "category": 2,
                            "requirements": [{
                                    "type": 1,
                                    "villageType": 2,
                                    "op": 0,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "43": {
                            "buildingType": 43,
                            "costs": {
                                "1": 120,
                                "2": 200,
                                "3": 0,
                                "4": 80
                            },
                            "time": 35,
                            "tribeId": 5,
                            "category": 2,
                            "requirements": null,
                            "canFinishInstantly": true
                        },
                        "44": {
                            "buildingType": 44,
                            "costs": {
                                "1": 1460,
                                "2": 930,
                                "3": 1250,
                                "4": 1740
                            },
                            "time": 690,
                            "tribeId": 2,
                            "category": 2,
                            "requirements": [{
                                    "type": 1,
                                    "villageType": 1,
                                    "op": 0,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 11,
                                    "minLvl": 20,
                                    "op": 5,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 16,
                                    "minLvl": 10,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": true
                        },
                        "45": {
                            "buildingType": 45,
                            "costs": {
                                "1": 720,
                                "2": 685,
                                "3": 645,
                                "4": 250
                            },
                            "time": 2040,
                            "tribeId": 0,
                            "category": 1,
                            "requirements": [{
                                    "type": 2,
                                    "buildingType": 27,
                                    "minLvl": -1,
                                    "op": 0,
                                    "valid": 0
                                }, {
                                    "type": 2,
                                    "buildingType": 15,
                                    "minLvl": 3,
                                    "op": 5,
                                    "valid": 0
                                }],
                            "canFinishInstantly": false
                        }
                    },
                    "celebrationConfig": {
                        "celebrationTypeSmall": 1,
                        "celebrationTypeBig": 2,
                        "celebrationTypeBrewery": 3,
                        "celebrationCultureBonusSmall": 500,
                        "celebrationCultureBonusBig": 2000,
                        "celebrationLoyaltyBonusBig": 5,
                        "publicFestivalAttackPercentBonus": 1,
                        "firstLevelOfTownHallForBigCelebration": 10,
                        "villageCountForBigCelebration": 2
                    },
                    "heroItems": {
                        "4": {
                            "slot": 1,
                            "images": ["helmet1_0"]
                        },
                        "5": {
                            "slot": 1,
                            "images": ["helmet1_1"]
                        },
                        "6": {
                            "slot": 1,
                            "images": ["helmet1_2"]
                        },
                        "7": {
                            "slot": 1,
                            "images": ["helmet2_0"]
                        },
                        "8": {
                            "slot": 1,
                            "images": ["helmet2_1"]
                        },
                        "9": {
                            "slot": 1,
                            "images": ["helmet2_2"]
                        },
                        "10": {
                            "slot": 1,
                            "images": ["helmet3_0"]
                        },
                        "11": {
                            "slot": 1,
                            "images": ["helmet3_1"]
                        },
                        "12": {
                            "slot": 1,
                            "images": ["helmet3_2"]
                        },
                        "13": {
                            "slot": 1,
                            "images": ["helmet4_0"]
                        },
                        "14": {
                            "slot": 1,
                            "images": ["helmet4_1"]
                        },
                        "15": {
                            "slot": 1,
                            "images": ["helmet4_2"]
                        },
                        "16": {
                            "slot": 2,
                            "images": ["sword0_0"],
                            "tribeId": 1,
                            "unitBonus": 1,
                            "bonusStrength": 3
                        },
                        "17": {
                            "slot": 2,
                            "images": ["sword0_1"],
                            "tribeId": 1,
                            "unitBonus": 1,
                            "bonusStrength": 4
                        },
                        "18": {
                            "slot": 2,
                            "images": ["sword0_2"],
                            "tribeId": 1,
                            "unitBonus": 1,
                            "bonusStrength": 5
                        },
                        "19": {
                            "slot": 2,
                            "images": ["sword1_0"],
                            "tribeId": 1,
                            "unitBonus": 2,
                            "bonusStrength": 3
                        },
                        "20": {
                            "slot": 2,
                            "images": ["sword1_1"],
                            "tribeId": 1,
                            "unitBonus": 2,
                            "bonusStrength": 4
                        },
                        "21": {
                            "slot": 2,
                            "images": ["sword1_2"],
                            "tribeId": 1,
                            "unitBonus": 2,
                            "bonusStrength": 5
                        },
                        "22": {
                            "slot": 2,
                            "images": ["sword2_0"],
                            "tribeId": 1,
                            "unitBonus": 3,
                            "bonusStrength": 3
                        },
                        "23": {
                            "slot": 2,
                            "images": ["sword2_1"],
                            "tribeId": 1,
                            "unitBonus": 3,
                            "bonusStrength": 4
                        },
                        "24": {
                            "slot": 2,
                            "images": ["sword2_2"],
                            "tribeId": 1,
                            "unitBonus": 3,
                            "bonusStrength": 5
                        },
                        "25": {
                            "slot": 2,
                            "images": ["sword3_0"],
                            "tribeId": 1,
                            "unitBonus": 5,
                            "bonusStrength": 9
                        },
                        "26": {
                            "slot": 2,
                            "images": ["sword3_1"],
                            "tribeId": 1,
                            "unitBonus": 5,
                            "bonusStrength": 12
                        },
                        "27": {
                            "slot": 2,
                            "images": ["sword3_2"],
                            "tribeId": 1,
                            "unitBonus": 5,
                            "bonusStrength": 15
                        },
                        "28": {
                            "slot": 2,
                            "images": ["lance0_0"],
                            "tribeId": 1,
                            "unitBonus": 6,
                            "bonusStrength": 12
                        },
                        "29": {
                            "slot": 2,
                            "images": ["lance0_1"],
                            "tribeId": 1,
                            "unitBonus": 6,
                            "bonusStrength": 16
                        },
                        "30": {
                            "slot": 2,
                            "images": ["lance0_2"],
                            "tribeId": 1,
                            "unitBonus": 6,
                            "bonusStrength": 20
                        },
                        "31": {
                            "slot": 2,
                            "images": ["spear0_0"],
                            "tribeId": 3,
                            "unitBonus": 1,
                            "bonusStrength": 3
                        },
                        "32": {
                            "slot": 2,
                            "images": ["spear0_1"],
                            "tribeId": 3,
                            "unitBonus": 1,
                            "bonusStrength": 4
                        },
                        "33": {
                            "slot": 2,
                            "images": ["spear0_2"],
                            "tribeId": 3,
                            "unitBonus": 1,
                            "bonusStrength": 5
                        },
                        "34": {
                            "slot": 2,
                            "images": ["sword4_0"],
                            "tribeId": 3,
                            "unitBonus": 2,
                            "bonusStrength": 3
                        },
                        "35": {
                            "slot": 2,
                            "images": ["sword4_1"],
                            "tribeId": 3,
                            "unitBonus": 2,
                            "bonusStrength": 4
                        },
                        "36": {
                            "slot": 2,
                            "images": ["sword4_2"],
                            "tribeId": 3,
                            "unitBonus": 2,
                            "bonusStrength": 5
                        },
                        "37": {
                            "slot": 2,
                            "images": ["bow0_0"],
                            "tribeId": 3,
                            "unitBonus": 4,
                            "bonusStrength": 6
                        },
                        "38": {
                            "slot": 2,
                            "images": ["bow0_1"],
                            "tribeId": 3,
                            "unitBonus": 4,
                            "bonusStrength": 8
                        },
                        "39": {
                            "slot": 2,
                            "images": ["bow0_2"],
                            "tribeId": 3,
                            "unitBonus": 4,
                            "bonusStrength": 10
                        },
                        "40": {
                            "slot": 2,
                            "images": ["staff0_0"],
                            "tribeId": 3,
                            "unitBonus": 5,
                            "bonusStrength": 6
                        },
                        "41": {
                            "slot": 2,
                            "images": ["staff0_1"],
                            "tribeId": 3,
                            "unitBonus": 5,
                            "bonusStrength": 8
                        },
                        "42": {
                            "slot": 2,
                            "images": ["staff0_2"],
                            "tribeId": 3,
                            "unitBonus": 5,
                            "bonusStrength": 10
                        },
                        "43": {
                            "slot": 2,
                            "images": ["spear1_0"],
                            "tribeId": 3,
                            "unitBonus": 6,
                            "bonusStrength": 9
                        },
                        "44": {
                            "slot": 2,
                            "images": ["spear1_1"],
                            "tribeId": 3,
                            "unitBonus": 6,
                            "bonusStrength": 12
                        },
                        "45": {
                            "slot": 2,
                            "images": ["spear1_2"],
                            "tribeId": 3,
                            "unitBonus": 6,
                            "bonusStrength": 15
                        },
                        "46": {
                            "slot": 2,
                            "images": ["club0_0"],
                            "tribeId": 2,
                            "unitBonus": 1,
                            "bonusStrength": 3
                        },
                        "47": {
                            "slot": 2,
                            "images": ["club0_1"],
                            "tribeId": 2,
                            "unitBonus": 1,
                            "bonusStrength": 4
                        },
                        "48": {
                            "slot": 2,
                            "images": ["club0_2"],
                            "tribeId": 2,
                            "unitBonus": 1,
                            "bonusStrength": 5
                        },
                        "49": {
                            "slot": 2,
                            "images": ["spear2_0"],
                            "tribeId": 2,
                            "unitBonus": 2,
                            "bonusStrength": 3
                        },
                        "50": {
                            "slot": 2,
                            "images": ["spear2_1"],
                            "tribeId": 2,
                            "unitBonus": 2,
                            "bonusStrength": 4
                        },
                        "51": {
                            "slot": 2,
                            "images": ["spear2_2"],
                            "tribeId": 2,
                            "unitBonus": 2,
                            "bonusStrength": 5
                        },
                        "52": {
                            "slot": 2,
                            "images": ["axe0_0"],
                            "tribeId": 2,
                            "unitBonus": 3,
                            "bonusStrength": 3
                        },
                        "53": {
                            "slot": 2,
                            "images": ["axe0_1"],
                            "tribeId": 2,
                            "unitBonus": 3,
                            "bonusStrength": 4
                        },
                        "54": {
                            "slot": 2,
                            "images": ["axe0_2"],
                            "tribeId": 2,
                            "unitBonus": 3,
                            "bonusStrength": 5
                        },
                        "55": {
                            "slot": 2,
                            "images": ["hammer0_0"],
                            "tribeId": 2,
                            "unitBonus": 5,
                            "bonusStrength": 6
                        },
                        "56": {
                            "slot": 2,
                            "images": ["hammer0_1"],
                            "tribeId": 2,
                            "unitBonus": 5,
                            "bonusStrength": 8
                        },
                        "57": {
                            "slot": 2,
                            "images": ["hammer0_2"],
                            "tribeId": 2,
                            "unitBonus": 5,
                            "bonusStrength": 10
                        },
                        "58": {
                            "slot": 2,
                            "images": ["sword5_0"],
                            "tribeId": 2,
                            "unitBonus": 6,
                            "bonusStrength": 9
                        },
                        "59": {
                            "slot": 2,
                            "images": ["sword5_1"],
                            "tribeId": 2,
                            "unitBonus": 6,
                            "bonusStrength": 12
                        },
                        "60": {
                            "slot": 2,
                            "images": ["sword5_2"],
                            "tribeId": 2,
                            "unitBonus": 6,
                            "bonusStrength": 15
                        },
                        "61": {
                            "slot": 3,
                            "images": ["map0_0"]
                        },
                        "62": {
                            "slot": 3,
                            "images": ["map0_1"]
                        },
                        "63": {
                            "slot": 3,
                            "images": ["map0_2"]
                        },
                        "64": {
                            "slot": 3,
                            "images": ["flag0_0"]
                        },
                        "65": {
                            "slot": 3,
                            "images": ["flag0_1"]
                        },
                        "66": {
                            "slot": 3,
                            "images": ["flag0_2"]
                        },
                        "67": {
                            "slot": 3,
                            "images": ["flag1_0"]
                        },
                        "68": {
                            "slot": 3,
                            "images": ["flag1_1"]
                        },
                        "69": {
                            "slot": 3,
                            "images": ["flag1_2"]
                        },
                        "70": {
                            "slot": 3,
                            "images": ["telescope0_0"]
                        },
                        "71": {
                            "slot": 3,
                            "images": ["telescope0_1"]
                        },
                        "72": {
                            "slot": 3,
                            "images": ["telescope0_2"]
                        },
                        "73": {
                            "slot": 3,
                            "images": ["sack0_0"]
                        },
                        "74": {
                            "slot": 3,
                            "images": ["sack0_1"]
                        },
                        "75": {
                            "slot": 3,
                            "images": ["sack0_2"]
                        },
                        "76": {
                            "slot": 3,
                            "images": ["shield0_0"]
                        },
                        "77": {
                            "slot": 3,
                            "images": ["shield0_1"]
                        },
                        "78": {
                            "slot": 3,
                            "images": ["shield0_2"]
                        },
                        "79": {
                            "slot": 3,
                            "images": ["horn0_0"]
                        },
                        "80": {
                            "slot": 3,
                            "images": ["horn0_1"]
                        },
                        "81": {
                            "slot": 3,
                            "images": ["horn0_2"]
                        },
                        "82": {
                            "slot": 4,
                            "images": ["shirt0_0"]
                        },
                        "83": {
                            "slot": 4,
                            "images": ["shirt0_1"]
                        },
                        "84": {
                            "slot": 4,
                            "images": ["shirt0_2"]
                        },
                        "85": {
                            "slot": 4,
                            "images": ["shirt1_0"]
                        },
                        "86": {
                            "slot": 4,
                            "images": ["shirt1_1"]
                        },
                        "87": {
                            "slot": 4,
                            "images": ["shirt1_2"]
                        },
                        "88": {
                            "slot": 4,
                            "images": ["shirt2_0"]
                        },
                        "89": {
                            "slot": 4,
                            "images": ["shirt2_1"]
                        },
                        "90": {
                            "slot": 4,
                            "images": ["shirt2_2"]
                        },
                        "91": {
                            "slot": 4,
                            "images": ["shirt3_0"]
                        },
                        "92": {
                            "slot": 4,
                            "images": ["shirt3_1"]
                        },
                        "93": {
                            "slot": 4,
                            "images": ["shirt3_2"]
                        },
                        "94": {
                            "slot": 5,
                            "images": ["shoes0_0"]
                        },
                        "95": {
                            "slot": 5,
                            "images": ["shoes0_1"]
                        },
                        "96": {
                            "slot": 5,
                            "images": ["shoes0_2"]
                        },
                        "97": {
                            "slot": 5,
                            "images": ["shoes1_0"]
                        },
                        "98": {
                            "slot": 5,
                            "images": ["shoes1_1"]
                        },
                        "99": {
                            "slot": 5,
                            "images": ["shoes1_2"]
                        },
                        "100": {
                            "slot": 5,
                            "images": ["shoes2_0"]
                        },
                        "101": {
                            "slot": 5,
                            "images": ["shoes2_1"]
                        },
                        "102": {
                            "slot": 5,
                            "images": ["shoes2_2"]
                        },
                        "103": {
                            "slot": 6,
                            "images": ["horse1_0"]
                        },
                        "104": {
                            "slot": 6,
                            "images": ["horse1_1"]
                        },
                        "105": {
                            "slot": 6,
                            "images": ["horse1_2"]
                        },
                        "106": {
                            "slot": 6,
                            "images": ["horse2_0"]
                        },
                        "107": {
                            "slot": 6,
                            "images": ["horse2_1"]
                        },
                        "108": {
                            "slot": 6,
                            "images": ["horse2_2"]
                        },
                        "109": {
                            "slot": 6,
                            "images": ["horse0_0"]
                        },
                        "110": {
                            "slot": 6,
                            "images": ["horse0_1"]
                        },
                        "111": {
                            "slot": 6,
                            "images": ["horse0_2"]
                        },
                        "112": {
                            "slot": -1,
                            "images": ["ointment"]
                        },
                        "113": {
                            "slot": -1,
                            "images": ["scroll"]
                        },
                        "114": {
                            "slot": -1,
                            "images": ["water_bucket"]
                        },
                        "115": {
                            "slot": -1,
                            "images": ["book"]
                        },
                        "116": {
                            "slot": -1,
                            "images": ["artwork"]
                        },
                        "117": {
                            "slot": 7,
                            "images": ["small_bandage"]
                        },
                        "118": {
                            "slot": 7,
                            "images": ["bandage"]
                        },
                        "119": {
                            "slot": 7,
                            "images": ["cage"]
                        },
                        "120": {
                            "slot": -1,
                            "images": ["treasures"]
                        },
                        "121": {
                            "slot": 5,
                            "images": ["shoes3_0"]
                        },
                        "122": {
                            "slot": 5,
                            "images": ["shoes3_1"]
                        },
                        "123": {
                            "slot": 5,
                            "images": ["shoes3_2"]
                        },
                        "124": {
                            "slot": 7,
                            "images": ["healing_potion"]
                        },
                        "125": {
                            "slot": -1,
                            "images": ["upgrade_armor"]
                        },
                        "126": {
                            "slot": -1,
                            "images": ["upgrade_weapon"]
                        },
                        "127": {
                            "slot": -1,
                            "images": ["upgrade_accessory"]
                        },
                        "128": {
                            "slot": -1,
                            "images": ["upgrade_helmet"]
                        },
                        "129": {
                            "slot": -1,
                            "images": ["upgrade_shoes"]
                        },
                        "130": {
                            "slot": -1,
                            "images": ["resourceChest3", "resourceChest4"]
                        },
                        "131": {
                            "slot": -1,
                            "images": ["resourceChest4", "resourceChest5"]
                        },
                        "132": {
                            "slot": -1,
                            "images": ["resourceChest5"]
                        },
                        "133": {
                            "slot": -1,
                            "images": ["cropChest3", "cropChest4"]
                        },
                        "134": {
                            "slot": -1,
                            "images": ["cropChest4", "cropChest5"]
                        },
                        "135": {
                            "slot": -1,
                            "images": ["cropChest5"]
                        },
                        "136": {
                            "slot": -1,
                            "images": ["adventure_point"]
                        },
                        "137": {
                            "slot": -1,
                            "images": ["building_ground"]
                        },
                        "138": {
                            "slot": -1,
                            "images": ["finishImmediately"]
                        },
                        "139": {
                            "slot": -1,
                            "images": ["npcTrader"]
                        },
                        "140": {
                            "slot": -1,
                            "images": ["instantDelivery"]
                        },
                        "141": {
                            "slot": 7,
                            "images": ["small_bandage"]
                        },
                        "142": {
                            "slot": 7,
                            "images": ["bandage"]
                        },
                        "maxUpgrades": 5
                    },
                    "exchangeOffice": {
                        "silverToGoldRate": 200,
                        "goldToSilverRate": 100,
                        "maxAmount": 99999
                    },
                    "premiumFeatureStarterPackage": 2,
                    "PremiumFeatures": {
                        "extendTimeBeforeExpires": 86400,
                        "estimatedWorldAge": 141,
                        "minimumPriceMultiplier": 5,
                        "discountPriceFactor": 0.714285714285,
                        "finishNow": {
                            "price": 2,
                            "priceAll": 3,
                            "priceReduced": 1,
                            "timeReduced": 7200,
                            "timeFree": 300,
                            "active": true
                        },
                        "exchangeOffice": {
                            "price": 1,
                            "active": true
                        },
                        "productionBonus": {
                            "price": 20,
                            "active": true,
                            "duration": 432000,
                            "durationSpeed": 259200,
                            "bonusValue": 25,
                            "bookableWholeGameRound": true
                        },
                        "cropProductionBonus": {
                            "price": 10,
                            "active": true,
                            "duration": 432000,
                            "durationSpeed": 259200,
                            "bonusValue": 25,
                            "bookableWholeGameRound": true
                        },
                        "NPCTrader": {
                            "price": 5,
                            "active": true
                        },
                        "tributeFetchInstantly": {
                            "price": 1,
                            "active": true
                        },
                        "plusAccount": {
                            "price": 10,
                            "active": true,
                            "duration": 432000,
                            "durationSpeed": 259200,
                            "bookableWholeGameRound": true
                        },
                        "starterPackage": {
                            "price": 60,
                            "active": true,
                            "deactivateAfterSignUp": 2592000,
                            "duration": 604800
                        },
                        "buildingMasterSlot": {
                            "price": 50,
                            "price2": 75,
                            "price3": 100,
                            "active": true
                        },
                        "treasureResourcesInstant": {
                            "price": 3,
                            "active": true
                        },
                        "traderArriveInstantly": {
                            "price": 2,
                            "priceMin": 2,
                            "priceMid": 2,
                            "priceMax": 2,
                            "minutesMin": 10,
                            "minutesMid": 60,
                            "active": true
                        },
                        "traderSlot": {
                            "price": 50,
                            "price2": 25,
                            "active": true
                        },
                        "cardgameSingle": {
                            "price": 5,
                            "active": true
                        },
                        "cardgame4of5": {
                            "price": 20,
                            "active": true
                        }
                    },
                    "fetchingDurationMultiplier": 3,
                    "paymentShopDisabled": false,
                    "natarVillageActivationTime": 9590400,
                    "endWorldWithWorldWonderLevel": 100,
                    "KingdomConfig": {
                        "treasureInfluenceBonuses": [{
                                "treasures": 100,
                                "factor": 1.25
                            }, {
                                "treasures": 200,
                                "factor": 1.35
                            }, {
                                "treasures": 300,
                                "factor": 1.5
                            }, {
                                "treasures": 400,
                                "factor": 1.65
                            }, {
                                "treasures": 500,
                                "factor": 1.8
                            }, {
                                "treasures": 600,
                                "factor": 2
                            }, {
                                "treasures": 700,
                                "factor": 2.2
                            }, {
                                "treasures": 800,
                                "factor": 2.4
                            }, {
                                "treasures": 900,
                                "factor": 2.6
                            }, {
                                "treasures": 1000,
                                "factor": 2.8
                            }, {
                                "treasures": 1250,
                                "factor": 3
                            }, {
                                "treasures": 1500,
                                "factor": 3.2
                            }, {
                                "treasures": 1750,
                                "factor": 3.4
                            }, {
                                "treasures": 2000,
                                "factor": 3.6
                            }, {
                                "treasures": 2250,
                                "factor": 3.8
                            }, {
                                "treasures": 2500,
                                "factor": 4
                            }, {
                                "treasures": 2750,
                                "factor": 4.2
                            }, {
                                "treasures": 3000,
                                "factor": 4.4
                            }, {
                                "treasures": 3500,
                                "factor": 4.6
                            }, {
                                "treasures": 4000,
                                "factor": 5
                            }],
                        "treasuresNeededForTreasurySlot": 4000,
                        "defaultTreasuriesForKingsAndDukes": 1,
                        "kingdomMaxDukes": 4,
                        "minimumStartTributes": 600,
                        "minimumStartTributesDays": 10
                    },
                    "charLimits": {
                        "shareMessageCharLimit": 160,
                        "societyInvitationCharLimit": 250
                    },
                    "NoobProtectionDays": {
                        "King": 1,
                        "Governor": 7
                    },
                    "DeletionAfterBuyingGoldTimeout": 604800,
                    "maxUsedDailyQuestSlots": 3,
                    "maxDailyQuestsExchanged": 1,
                    "worldRadius": "80",
                    "teahouse": "false",
                    "direction": "ltr",
                    "tzone_offset": 1,
                    "defaultTimeFormat": 0,
                    "tgPaymentDefaultShopVersion": 1,
                    "apiVersion": "0.66.9",
                    "gameLobbyUrl": "<?php echo protocalRemove($lobby_url);?>",
                    "selectableLanguages": {
                        "de": "Deutsch",
                        "en": "English",
                        "cz": "\u010ce\u0161tina",
                        "dk": "Dansk",
                        "fr": "Fran\u00e7ais",
                        "hu": "Magyar",
                        "it": "Italiano",
                        "nl": "Nederlands",
                        "pl": "Polski",
                        "ru": "\u0420\u0443\u0441\u0441\u043a\u0438\u0439",
                        "tr": "T\u00fcrk\u00e7e",
                        "ae": "\u0627\u0644\u0639\u0631\u0628\u064a\u0629",
                        "no": "Norsk",
                        "se": "Svenska",
                        "fi": "Suomi"
                    },
                    "countryId": "en",
                    "prestige": {
                        "25": {
                            "bronze": 1,
                            "silver": 0,
                            "gold": 0
                        },
                        "50": {
                            "bronze": 2,
                            "silver": 0,
                            "gold": 0
                        },
                        "100": {
                            "bronze": 3,
                            "silver": 0,
                            "gold": 0
                        },
                        "200": {
                            "bronze": 3,
                            "silver": 1,
                            "gold": 0
                        },
                        "300": {
                            "bronze": 3,
                            "silver": 2,
                            "gold": 0
                        },
                        "400": {
                            "bronze": 0,
                            "silver": 3,
                            "gold": 0
                        },
                        "500": {
                            "bronze": 0,
                            "silver": 3,
                            "gold": 1
                        },
                        "750": {
                            "bronze": 0,
                            "silver": 3,
                            "gold": 2
                        },
                        "1000": {
                            "bronze": 0,
                            "silver": 0,
                            "gold": 3
                        },
                        "2000": {
                            "bronzeBadge": 1,
                            "bronze": 0,
                            "silver": 0,
                            "gold": 0
                        },
                        "5000": {
                            "silverBadge": 1,
                            "bronze": 0,
                            "silver": 0,
                            "gold": 0
                        },
                        "10000": {
                            "goldBadge": 1,
                            "bronze": 0,
                            "silver": 0,
                            "gold": 0
                        }
                    },
                    "trackingActive": true,
                    "siegeSpeedFactor": 2,
                    "minPrestigeForGetAKing": 25,
                    "auction": {
                        "previousOwnersPriceInfluence": [1, 0.75, 0.5, 0.33, 0.25]
                    },
                    "kingdomAllianceMerge": true,
                    "disableKingdomChangesWithWWLevel": 50,
                    "cdnPrefix": "<?php echo $cdn_url . $apiversion; ?>/"
                };
                Travian.Config.worldRadius = 80;
                Travian.Config.teahouse = false;
                Travian.Config.questVersion = 2;

                WEB_SOCKET_SWF_LOCATION = "./WebSocketMain.swf";
                for (var i = 0; i < onConfigLoaded.length; i++) {
                    if (typeof (onConfigLoaded[i]) == "function") {
                        onConfigLoaded[i]();
                    }
                }
            });
            fileLoader.addScript('<?php echo $mellon_url; ?>tk/fenster-css.css', function () {}, 'css');
            fileLoader.addScript('<?php echo $mellon_url; ?>tk/fenster-js.js', function () {}, 'js');
            fileLoader.addScript('<?php echo $mellon_url; ?>tk/sdk-js.js', function () {}, 'js');
            fileLoader.addScript('<?php echo $cdn_url . $apiversion; ?>/js/quests_version2.js?h=2c0ce034fb6e25a659446203e61ea077');
            fileLoader.addScript('<?php echo $cdn_url . $apiversion; ?>/lang/th.js?h=0.66.9', function () {}, 'js');
            fileLoader.addScript('<?php echo $cdn_url . $apiversion; ?>/lang/th_quests_version2.js?h=0.66.9', function () {}, 'js');
            fileLoader.addScript('<?php echo $cdn_url . $apiversion; ?>/layout/css/ltr.css?h=2c0ce034fb6e25a659446203e61ea077')
        </script>


        <link ng-if="config['SERVER_ENV'] != 'live'" rel="stylesheet" href="<?php echo $cdn_url . $apiversion; ?>/layout/css/cheatSheetBase.css" type="text/css">
        <!--<link rel="icon" href="layout/favicon.ico?v=5" type="image/x-icon">-->

        <!-- apple related favicons and settings -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="apple-touch-icon" sizes="57x57" href="http://cdn.traviantools.net/game/0.66/layout/favicons/apple-touch-icon-57x57.png?v=2">
        <link rel="apple-touch-icon" sizes="60x60" href="http://cdn.traviantools.net/game/0.66/layout/favicons/apple-touch-icon-60x60.png?v=2">
        <link rel="apple-touch-icon" sizes="72x72" href="http://cdn.traviantools.net/game/0.66/layout/favicons/apple-touch-icon-72x72.png?v=2">
        <link rel="apple-touch-icon" sizes="76x76" href="http://cdn.traviantools.net/game/0.66/layout/favicons/apple-touch-icon-76x76.png?v=2">
        <link rel="apple-touch-icon" sizes="114x114" href="http://cdn.traviantools.net/game/0.66/layout/favicons/apple-touch-icon-114x114.png?v=2">
        <link rel="apple-touch-icon" sizes="120x120" href="http://cdn.traviantools.net/game/0.66/layout/favicons/apple-touch-icon-120x120.png?v=2">
        <link rel="apple-touch-icon" sizes="144x144" href="http://cdn.traviantools.net/game/0.66/layout/favicons/apple-touch-icon-144x144.png?v=2">
        <link rel="apple-touch-icon" sizes="152x152" href="http://cdn.traviantools.net/game/0.66/layout/favicons/apple-touch-icon-152x152.png?v=2">
        <link rel="apple-touch-icon" sizes="180x180" href="http://cdn.traviantools.net/game/0.66/layout/favicons/apple-touch-icon-180x180.png?v=2">
        <link rel="mask-icon" href="http://cdn.traviantools.net/game/0.66/layout/favicons/safari-pinned-tab.svg?v=2" color="#5bbad5">

        <!-- android related favicons and settings -->
        <link rel="manifest" href="http://cdn.traviantools.net/game/0.66/layout/favicons/manifest.json?v=2">
        <meta name="theme-color" content="#7DA100">

        <!-- windows phone related favicons and settings -->
        <meta name="msapplication-tap-highlight" content="no" />
        <meta name="msapplication-config" content="http://cdn.traviantools.net/game/0.66/layout/favicons/browserconfig.xml?v=2" />
        <meta name="application-name" content="Travian Kingdoms" />
        <meta name="msapplication-TileColor" content="#da532c" />
        <meta name="msapplication-TileImage" content="http://cdn.traviantools.net/game/0.66/layout/favicons/mstile-144x144.png?v=2" />

        <!-- normal favicons -->
        <link rel="icon" type="image/x-icon" href="http://cdn.traviantools.net/game/0.66/layout/favicons/favicon.ico?v=2">
        <link rel="icon" type="image/png" href="http://cdn.traviantools.net/game/0.66/layout/favicons/favicon-32x32.png?v=2" sizes="32x32">
        <link rel="icon" type="image/png" href="http://cdn.traviantools.net/game/0.66/layout/favicons/favicon-194x194.png?v=2" sizes="194x194">
        <link rel="icon" type="image/png" href="http://cdn.traviantools.net/game/0.66/layout/favicons/favicon-96x96.png?v=2" sizes="96x96">
        <link rel="icon" type="image/png" href="http://cdn.traviantools.net/game/0.66/layout/favicons/favicon-16x16.png?v=2" sizes="16x16">

        <script>
            isIE8 = false;
        </script>
        <!--[if lte IE 8]>
        <script type="text/javascript">
                fileLoader.addScript('http://cdn.traviantools.net/game/0.66/layout/css/ie.css');
                fileLoader.addScript('http://cdn.traviantools.net/game/0.66/js/ie-min.js');
                isIE8 = true;
        </script>
        <![endif]-->

        <style type="text/css">
            body {
                overflow: hidden;
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-weight: normal;
                font-size: 13px;
                line-height: 16px;
            }

            #loadingOverlay {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                padding: 20px;
                z-index: 1060;
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 14px;
                background-color: #9CA55B;
                background-image: url(http://cdn.traviantools.net/game/0.66/layout/images/illustration/loadingScreen/loading_screen_logo.png);
                background-repeat: no-repeat;
                background-position: center;
            }

            .loadingScreen {
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                z-index: 1060;
                position: fixed;
                background-color: #9ca55b;
                color: #463f39;
                direction: ltr;
            }

            .loadingScreen .centerArea {
                width: 100%;
                height: 1000px;
                position: absolute;
                top: 50%;
                margin-top: -500px;
            }

            .loadingScreen .centerArea .highlight {
                width: 100%;
                max-width: 647px;
                background: -moz-radial-gradient(50% 50%, circle closest-side, #D9DBC6 0%, #9CA55B 100%);
                background-image: -webkit-radial-gradient(50% 50%, circle closest-side, #D9DBC6 0%, #9CA55B 100%);
                background: -ms-radial-gradient(50% 50%, circle closest-side, #D9DBC6 0%, #9CA55B 100%);
                background: radial-gradient(50% 50%, circle closest-side, #D9DBC6 0%, #9CA55B 100%);
                height: 629px;
                margin: 0 auto;
                top: 21px;
                position: relative;
            }

            .loadingScreen .centerArea .loadingBar {
                background-color: #ccbb8f;
                height: 58px;
                position: absolute;
                top: 50%;
                width: 100%;
                background-color: rgba(0, 0, 0, 0.33);
                margin-top: -29px;
                text-align: center;
            }

            .loadingScreen.unload .centerArea .loadingBar {
                display: none;
            }

            .loadingScreen .centerArea .loadingBar .loadingPercent {
                display: inline-block;
                font-size: 22px;
                left: 2px;
                bottom: 19px;
                position: relative;
                width: 50px;
                color: #f1f0ee;
            }

            .loadingScreen .centerArea .loadingBar .progressBar {
                width: 211px;
                height: 58px;
                position: relative;
                display: inline-block;
            }

            .loadingScreen .loadingBar .progressBar .sword.transparent {
                opacity: 0.4;
                width: 211px;
            }

            .loadingScreen .loadingBar .progressBar .sword {
                height: 100%;
                width: 48px;
                position: absolute;
                background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAM8AAAAvBAMAAAC264PuAAAAMFBMVEUAAADm5ubZ19dlUET08/PNy8uqpae1sbPCv8Gclpp5Y1WCeXW+u7mddlujmpa2h2W5fRWkAAAAAXRSTlMAQObYZgAAAkhJREFUWMPV2MFr01AcB/AfvJu3dxg48NIMpaJIZpRJqgel/4CWN1BPOnwdbAi2aER7sVAJehSEXEYht4DaLq7t3BBkIAPjyZsQBIsDC/YPGEjMS7KRJ2SO8fJkn0Ogh/KlL7/3+71X+H+QBlKg0gikQKsLIAV6T1WQAfXmZQUtdkAG1KvWQQbUN6ZAAhZ0CmRA696sjGpgQU05QQPzSRtyof0d1BxCHtCSzRdDi1T234AzOQz3DkoPn6tcr1vEk1oG3WHWmFUrtDQeU0oNo+F5LRJSFAVjXN51M71ar8m1Dhdkvby3RWnVMDxv0zSTrx/QSS1VZYRMcZ3h59ZtLMwZGxJohZBJNSqKWH84c18R58RO0pEfpxVFSxnMXWgdJ+LMuhDRa/XlgpsKWqmfJ0VToKeuGgU13Rk85zi6k1iun1WKhkgPoiT9ql3Cz3z/lf81fnQ3pvFRKtY7NQwqaCV8I/j9JtgOAvZ4WzlXnrAEs9X4F30Otr8FPkuLg66UxXqksqVzL+Jf/hffv8QWz/G7lWmMhcZcHqlR1W10cVh1jq07NqsJ9o4KWKCJF2q0j74X+X3Eqk5k0DF3pzMoSrozsA3bFNoZsnpd/261pghz3U537zbXVCn9YIXG47iHR0PANFvkID5pe8yjeW4e8ZzEWsJKplI8lxg2W3YtaNxh2wbuo9GowH79e8Jmnxl69M4Q8seCbrUhfyyopkL+WFD2SfVwnr3l3SYaku5H65sdkIAFSbrDDj7Kuiw/BimQPoK9Hbb/gvLyB0UOPQQTizs9AAAAAElFTkSuQmCC') no-repeat left center;
            }

            .loadingScreen .centerArea .logo {
                background-image: url(http://cdn.traviantools.net/game/0.66/layout/images/illustration/loadingScreen/loading_screen_logo.png);
                width: 236px;
                height: 202px;
                margin: 0 auto;
                position: relative;
                top: 188px;
            }

            @media (max-height: 600px) {
                .loadingScreen .centerArea .logo {
                    top: 240px;
                }
            }

            .loadingScreen .centerArea .loadingText {
                text-align: center;
                position: relative;
                bottom: 56px;
            }

            .loadingScreen.unload .centerArea .loadingText {
                bottom: 156px;
            }

            .loadingScreen .centerArea .loadingText .action {
                font-weight: bold;
                font-size: 22px;
            }

            .loadingScreen.unload .centerArea .loadingText .action {
                display: none;
            }

            .loadingScreen .centerArea .loadingText .action.backToLobby {
                display: none;
            }

            .loadingScreen.unload .centerArea .loadingText .action.backToLobby {
                display: block;
            }

            .loadingScreen .centerArea .loadingText .avatarOnGameworld {
                font-size: 22px;
                line-height: 0;
            }

            .loadingScreen .centerArea .loadingText .errorMessage {
                padding-bottom: 31px;
                line-height: 22px;
            }

            .loadingScreen .centerArea .loadingText .randomText {
                font-size: 18px;
                font-style: italic;
                line-height: 35px;
            }

            .loadingScreen.unload .centerArea .loadingText .randomText.backToLobby {
                display: block;
            }

            .loadingScreen.unload .centerArea .loadingText .randomText {
                display: none;
            }

            .loadingScreen .centerArea .loadingText .randomText.backToLobby {
                display: none;
            }

            .loadingScreen .centerArea .loadingText hr {
                width: 560px;
                border-top: 1px solid #7b736d;
                border-bottom: 1px solid #ABA7A4;
                margin-top: -13px;
                display: block;
            }

            .loadingScreen .centerArea .loadingText>div {
                height: 40px;
            }
        </style>

        <script>
            // Load all the files
            fileLoader.load(
                    function () {
                        //we have to wait until all files have been loaded until we manually bootstrap angular
                        angular.bootstrap(document.documentElement, ['t5']);
                        Travian.checkPage();
                    }
            );
        </script>

    </head>

    <body class="env-live">
        <svg>
    <filter id="shapeBlur">
        <feGaussianBlur in="SourceGraphic" stdDeviation="2" />
    </filter>
    <filter id="locationBlur">
        <feGaussianBlur in="SourceGraphic" stdDeviation="10" />
    </filter>
    </svg>

    <div id="loadingOverlay"></div>

    <!-- ng-view muss leider existieren, damit das Routing funktioniert. Wird ansonsten aber nicht genutzt	-->
    <ng-view></ng-view>

    <!-- Header -->

    <div class="mainContentWrapper" ng-include src="'tpl/mainlayout/mainContent.html'"></div>

    <div class="loadingScreen">
        <div class="centerArea">
            <div class="highlight">
                <div class="logo"></div>
            </div>
            <div class="loadingBar">
                <div class="progressBar">
                    <div class="sword transparent"></div>
                    <div class="sword fullVisible"></div>
                </div>
                <div class="loadingPercent">
                    <span class="loadingTextNumber">0</span><span>%</span>
                </div>
            </div>
            <div class="loadingText">
                <div class="action backToLobby">
                    <translate>LoadingScreen.Action.Logout</translate>
                </div>
                <div class="action loadingGame">LOADING</div>
                <div class="avatarOnGameworld">
                    phoomin009 on <?php echo $engine->server->name;?> </div>
                <hr />
                <div class="randomText backToLobby">
                    <translate>LoadingScreen.Action.Logout.RandomText</translate>
                </div>
                <div class="randomText">
                    Building Wonders of the World </div>
                <div class="errorMessage"></div>
            </div>
        </div>
    </div>

    <div class="windowLoading loading" id="windowLoadingSpinner">
        <div class="spin-1 spinner"></div>
    </div>
    <div class="pendingAjaxRequest loading">
        <div class="spin-2 spinner"></div>
    </div>
    <div id="jqFensterModalLayout" class="jqFensterModal">
        <div class="jqFensterModalContent"></div>
    </div>
    <!-- include RUM -->

</html>