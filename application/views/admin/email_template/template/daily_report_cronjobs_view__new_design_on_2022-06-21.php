<?php //print_r($user_wise_stage); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title></title>
      <style>
         /* A simple css reset */
      </style>
   </head>
   <body>
      <div style="width:900px; height: auto;display: block;margin: 0 auto;">
         <table width="100%" align="center">
            <tbody>
               <tr>
                  <td class="wrapper" width="600" align="center">
                     <!-- Header image -->
                     <table class="section header" border="0" cellpadding="0" cellspacing="0" width="800" style="border:none;">
                        <tr>
                           <td>
                              <h2 style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 26px; font-weight: 700; color: #FFF; text-align: center; margin: 0; background: #1b79cd; padding: 15px 0; border-radius: 10px 10px 0 0;">Day Report - <?php echo date_db_format_to_display_format($report_date); ?></h2>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="10" cellspacing="1" width="100%" border="1" style="border: #FFF 1px solid;">
                                 <thead>
                                    <tr style="background-color: #4472c4;">
                                       <th width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #FFF; text-align: left; border: none;box-sizing: border-box; padding: 10px 25px;">
                                          Overall Day Reports
                                       </th>
                                       <th width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          Count
                                       </th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                    <tr style="background-color: #cfd5ea;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          New Leads
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                       <?php echo $new_lead_report['new_lead_count']; ?>
                                       </td>
                                    </tr>
                                     <tr style="background-color: #e9ebf5;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          Leads Updated
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                       <?php echo $updated_lead_report['updated_lead_count']; ?>
                                       </td>
                                     </tr>
                                     <tr style="background-color: #cfd5ea;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          Deal Won
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          2
                                       </td>
                                    </tr>
                                     <tr style="background-color: #e9ebf5;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          Revenue (INR)
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          15000
                                       </td>
                                     </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td style="height:30px"></td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="10" cellspacing="1" width="100%" border="1" style="border: #FFF 1px solid;">
                                 <thead>
                                    <tr style="background-color: #4472c4;">
                                       <th width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #FFF; text-align: left; border: none;box-sizing: border-box; padding: 10px 25px;">
                                          Lead Stage Updated
                                       </th>
                                       <th width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          Count
                                       </th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                    <tr style="background-color: #cfd5ea;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          PENDING
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          23
                                       </td>
                                    </tr>
                                     <tr style="background-color: #e9ebf5;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          PROSPECT
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          150
                                       </td>
                                     </tr>
                                     <tr style="background-color: #cfd5ea;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          CONTACTED
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          2
                                       </td>
                                    </tr>
                                    <tr style="background-color: #e9ebf5;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          DEMO SCHEDULED
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          15000
                                       </td>
                                    </tr>

                                    <tr style="background-color: #cfd5ea;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          DEMO RESCHEDULED
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          2
                                       </td>
                                    </tr>
                                    <tr style="background-color: #e9ebf5;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          DEMO DONE
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          15000
                                       </td>
                                    </tr>
                                    <tr style="background-color: #cfd5ea;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          MEETING SCHEDULED
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          2
                                       </td>
                                    </tr>
                                    <tr style="background-color: #e9ebf5;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          DEMO SCHEDULED
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          15000
                                       </td>
                                    </tr>
                                    <tr style="background-color: #cfd5ea;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          MEETING DONE
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          2
                                       </td>
                                    </tr>
                                    <tr style="background-color: #e9ebf5;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          QUOTED
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          15000
                                       </td>
                                    </tr>
                                    <tr style="background-color: #cfd5ea;">
                                         <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          DEAL WON
                                          </td>
                                       <td width="50%" style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 18px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 25px;">
                                          2
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td style="height:30px"></td>
                        </tr>

                        <tr>
                           <td>
                              <div style="width: 800px; overflow: hidden; overflow-x: scroll;">
                                 <div >
                                    <table cellpadding="10" cellspacing="1" width="100%" border="1" style="border: #FFF 1px solid;">
                                       <thead>
                                          <tr style="background-color: #4472c4;">
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center; border: none;box-sizing: border-box; padding: 10px 5px;">
                                                User <br>Report
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                New Leads
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                Updated <br>Leads
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                PENDING
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                PROSPECT
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                CONTACTED
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                DEMO <br>SCHEDULED
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                DEMO <br>DONE
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                MEETING <br>SCHEDULED
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                MEETING <br>DONE
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                QUOTED
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                DEAL WON
                                             </th>
                                             <th style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #FFF; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                REVENUE
                                             </th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                          <tr style="background-color: #cfd5ea;">
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                PENDING
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                23
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                PENDING
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                23
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                PENDING
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                23
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                PENDING
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                23
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                PENDING
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                23
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                PENDING
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: center;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                23
                                             </td>
                                             <td style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; font-weight: 400; color: #000; text-align: left;border: none;box-sizing: border-box; padding: 10px 5px;">
                                                PENDING
                                             </td>
                                             
                                          </tr> 
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </td>
                        </tr>

                     </table>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </body>
</html>