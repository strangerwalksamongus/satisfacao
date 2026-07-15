<?php
class PluginSatisfacaoResponsePage extends CommonDBTM
{
   public function displayPage(): string
   {
      // $ticket_id = PluginSatisfacaoSurveyLink::getTicketIdBySatisfactionSurveyHash($_GET['satisfaction'] ?? '');
      $hash      = $_GET['satisfaction'] ?? '';
      $ticket_id = PluginSatisfacaoSurveyLink::getTicketIdBySatisfactionSurveyHash($hash);

      if (!$this->satisfactionSurveyValidation() || empty($ticket_id)) {
         return '<div style="text-align: center;">' . __('Sorry, something went wrong. Please contact the page administrator.', 'satisfacao') . '</div>';
      }

      if ($this->setSatisfactionSurveyAnswer($ticket_id, (int)($_GET['satisfactionLevel'] ?? 0))) {
         // Single-use: remove the token so the link cannot be reused or replayed.
         PluginSatisfacaoSurveyLink::deleteSatisfactionSurveyHash($hash);
         return '<div style="text-align: center;">' . __('Your response has been saved. Thank you for completing the satisfaction survey.', 'satisfacao') . '</div>';
      }

      return '<div style="text-align: center;">' . __('Sorry, we could not save your response. Please contact the page administrator.', 'satisfacao') . '</div>';
   }

   private function setSatisfactionSurveyAnswer($ticket_id, int $answer): bool
   {
      global $DB;

      // Old: returned true even when 0 rows matched (e.g. no satisfaction row
      // exists for the ticket), producing a false "response saved" message.
      // return $DB->update(
      //    'glpi_ticketsatisfactions',
      //    ['satisfaction' => $answer, 'date_answered' => date('Y-m-d H:i:s')],
      //    ['tickets_id' => $ticket_id]
      // );
      $DB->update(
         'glpi_ticketsatisfactions',
         ['satisfaction' => $answer, 'date_answered' => date('Y-m-d H:i:s')],
         ['tickets_id' => $ticket_id]
      );

      return $DB->affectedRows() > 0;
   }

   private function satisfactionSurveyValidation(): bool
   {
      if (!isset($_GET['satisfaction']) || !isset($_GET['satisfactionLevel'])) {
         return false;
      }

      $satisfactionLevel = (int)$_GET['satisfactionLevel'];
      if ($satisfactionLevel > 5 || $satisfactionLevel < 1) {
         return false;
      }

      return true;
   }
}
