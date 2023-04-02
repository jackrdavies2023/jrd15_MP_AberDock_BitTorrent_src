<html lang="en">
    {include file='header.tpl'}
    <body>
        {include file='navbar.tpl'}

        <!-- Contains the main page contents as well as navigation bar. -->
        <mainContainer>
            <!-- Contains main page content. -->
            <main>
                <titleBar>
                    <h1>Statistics</h1>
                </titleBar>

                <statisticsSummary>
                    <card class="no-background">
                        <statisticsSummaryBoxContainer>
                            
                            <statisticsSummaryBox>
                                <div>
                                    <span>Total P2P traffic today<br></span>

                                    <card>
                                        3000TiB
                                    </card>
                                </div>
                            </statisticsSummaryBox>

                            
                            <statisticsSummaryBox>
                                <div>
                                    <span>Total P2P traffic this week<br></span>

                                    <card>
                                        6000TiB
                                    </card>
                                </div>
                            </statisticsSummaryBox>

                            
                            <statisticsSummaryBox>
                                <div>
                                    <span>New uploads this week<br></span>

                                    <card>
                                        200
                                    </card>
                                </div>
                            </statisticsSummaryBox>

                            
                            <statisticsSummaryBox>
                                <div>
                                    <span>Total peers<br></span>

                                    <card>
                                        4323
                                    </card>
                                </div>
                            </statisticsSummaryBox>
                        </statisticsSummaryBoxContainer>
                    </card>
                </statisticsSummary>

                <!-- Top 10 seeders -->
                <h2>Top 10 seeders</h2>
                <topTenSeeders>
                    <card class="rounded-top-corners margin-top-2mm">
                        <statisticsTableHeader>
                            <span class="username">Username</span>
                            <span>Ratio</span>
                            <span>Upload</span>
                            <span>Download</span>
                        </statisticsTableHeader>
                    </card>
                    <card class="no-rounded-corners">
                        <statisticsTableHeader>
                            <span class="username">A human</span>
                            <span>5</span>
                            <span>600GiB</span>
                            <span>100GiB</span>
                        </statisticsTableHeader>
                    </card>
                    <card class="no-rounded-corners">
                        <statisticsTableHeader>
                            <span class="username">Another human</span>
                            <span>4</span>
                            <span>500GiB</span>
                            <span>200GiB</span>
                        </statisticsTableHeader>
                    </card>
                </topTenSeeders>

                <smallSeperator></smallSeperator>

                <!-- Top 10 worst seeders -->
                <h2>Top 10 worst seeders</h2>
                <topTenWorstSeeders>
                    <card class="rounded-top-corners margin-top-2mm">
                        <statisticsTableHeader>
                            <span class="username">Username</span>
                            <span>Ratio</span>
                            <span>Upload</span>
                            <span>Download</span>
                        </statisticsTableHeader>
                    </card>
                    <card class="no-rounded-corners">
                        <statisticsTableHeader>
                            <span class="username">A bad human</span>
                            <span>0</span>
                            <span>1GiB</span>
                            <span>600GiB</span>
                        </statisticsTableHeader>
                    </card>
                    <card class="no-rounded-corners">
                        <statisticsTableHeader>
                            <span class="username">Another very bad human</span>
                            <span>0</span>
                            <span>1GiB</span>
                            <span>500GiB</span>
                        </statisticsTableHeader>
                    </card>
                </topTenWorstSeeders>
            </main>
        </mainContainer>
    </body>
</html>