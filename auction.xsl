<?xml version="1.0"?><!-- DWXMLSource="results.xml" --> 
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"> 
    <xsl:output method="html"/> 
    <xsl:template match="/"> 
        <HTML> 
            <HEAD> 
                <TITLE> Units</TITLE> 
            </HEAD> 
            <BODY> <BR/>
                
                <table border="1">
                    <tr>
                      <th>Customer ID</th>
                      <th>Bidder ID</th>
                      <th>Item ID</th>
                      <th>Item Name</th>
                      <th>Category</th>
                      <th>Start Price</th>
                      <th>Reserved Price</th>
                      <th>Buy It Now Price</th>
                      <th>Sold Price</th>
                      <th>Duration</th>
                      <th>Status</th>
                      <th>Start Date</th>
                      <th>Start Time</th>
                      <th>Revenue</th>


                    </tr>
                    <xsl:for-each select="/Listings/ListedItem[Status!='in progress']">
                        <tr ><td><xsl:value-of select="SellerID"/></td>
                             <td><xsl:value-of select="BidderID"/></td>
                             <td><xsl:value-of select="ItemID"/></td>
                             <td><xsl:value-of select="ItemName"/></td>
                             <td><xsl:value-of select="Category"/></td>
                             <td><xsl:value-of select="StartPrice"/></td>
                             <td><xsl:value-of select="ReservePrice"/></td>
                             <td><xsl:value-of select="BuyItNowPrice"/></td>
                             <td><xsl:value-of select="BidPrice"/></td>
                             <td><xsl:value-of select="Duration"/></td>
                             <td><xsl:value-of select="Status"/></td>
                             <td><xsl:value-of select="CurrentDate"/></td>
                             <td><xsl:value-of select="CurrentTime"/></td>
                         <!--    https://stackoverflow.com/questions/13622338/how-to-implement-if-else-statement-in-xslt -->
                         <xsl:choose>
                            <xsl:when test="Status ='sold'">
                                <td><xsl:value-of select="BidPrice*0.03"/></td>
                            </xsl:when>
                            <xsl:otherwise>
                                <td><xsl:value-of select="ReservePrice*0.01"/></td>
                            </xsl:otherwise>
                        </xsl:choose>                             
                        </tr>
                    </xsl:for-each>
                </table>

                <BR/> 
                Total Sold Items: <xsl:value-of select="count(/Listings/ListedItem[Status='sold'])"/>
                <BR/><BR/>
                Total Failed Items: <xsl:value-of select="count(/Listings/ListedItem[Status='failed'])"/>
                <BR/><BR/>
                Total Revenue: <xsl:value-of select="format-number((sum(Listings/ListedItem[Status='sold']/BidPrice)*0.03 + sum(Listings/ListedItem[Status='failed']/ReservePrice)*0.01),'0.00')"/>
            </BODY> 
        </HTML> 
    </xsl:template>
</xsl:stylesheet>
