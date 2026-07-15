<?php
class PluginSatisfacaoResponsePage extends CommonDBTM
{
   public function displayPage(): string
   {
      $ticket_id = PluginSatisfacaoSurveyLink::getTicketIdBySatisfactionSurveyHash($_GET['satisfaction'] ?? '');
      
      if (!$this->satisfactionSurveyValidation() || empty($ticket_id)) {
         return '<div style="text-align: center;">' . __('Sorry, something went wrong. Please contact the page administrator.', 'satisfacao') . '</div>';
      }

      if ($this->setSatisfactionSurveyAnswer($ticket_id, (int)($_GET['satisfactionLevel'] ?? 0))) {
         return '<div style="text-align: center;">' . __('Your response has been saved. Thank you for completing the satisfaction survey.', 'satisfacao') . '</div>';
      }

      return '<div style="text-align: center;">' . __('Sorry, we could not save your response. Please contact the page administrator.', 'satisfacao') . '</div>';
   }

   private function setSatisfactionSurveyAnswer($ticket_id, int $answer): bool
   {
      global $DB;

      return $DB->update(
         'glpi_ticketsatisfactions',
         ['satisfaction' => $answer, 'date_answered' => date('Y-m-d H:i:s')],
         ['tickets_id' => $ticket_id]
      );
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
