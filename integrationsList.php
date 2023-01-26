<?php
    require_once 'vendor/autoload.php';
    require_once 'integrationAppToken.php';
    use GuzzleHttp\Client;
    use GuzzleHttp\Psr7\Request;

    $httpClient = new Client();
    $integrationAppAccessToken = getIntegrationAppToken();
?>

<html>
    <head>

        <!-- Import Integration.app SDK -->
        <script src="https://unpkg.com/@integration-app/sdk@0.16.25/bundle.js"></script>

        <script>
            // Initialize IntegrationApp SDK using token that we've generated in integrationAppToken.php
            iApp.init({token: "<?php echo($integrationAppAccessToken) ?>",})



            async function connectIntegration(integrationKey) {
                // Function for opening Connection Popup UI
                await iApp.integration(integrationKey).connect()
                location.reload()
            }

            async function disconnectIntegration(integrationKey) {
                // Disconnect Integration (equal to archiving connection)
                await iApp.integration(integrationKey).disconnect()
                location.reload()
            }

        </script>
    </head>
    <body>
        <table>
            <tbody>
                <?php

                $request = new Request('GET', 'https://engine-api.integration.app/integrations', [
                    'Authorization' => sprintf('Bearer %s', $integrationAppAccessToken),
                ]);
                $response = $httpClient->send($request);
                $integrationsArray = json_decode($response->getBody())->items;

                foreach ($integrationsArray as $integration) {?>
                    <tr>
                        <td>
                            <img src="<?php echo($integration->logoUri)?>" style="max-height: 50px"/>
                        </td>
                        <td>
                            <?php echo($integration->name) ?>
                        </td>
                        <td>
                            <?php if (property_exists($integration,'connection')) { ?>
                                <button onclick="disconnectIntegration('<?php echo($integration->key); ?>')">
                                    Disconnect
                                </button>
                            <?php } else { ?>
                                <button onclick="connectIntegration('<?php echo($integration->key); ?>')">
                                    Connect
                                </button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>