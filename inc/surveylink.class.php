<?php
class PluginSatisfacaoSurveyLink
{
   const SATISFACAO_SURVEY_HASHES_TABLE = 'glpi_plugin_satisfacao_hashes';

   public $satisfaction_survey_link;
   private $ticket_id;
   private $user_id;
   private $satisfaction_hash;

   public function __construct($user_id, $ticket_id)
   {
      $this->user_id = $user_id;
      $this->ticket_id = $ticket_id;

      $this->prepareSatisfactionSurveyLink();
      $this->saveSatisfactionSurveyHash();
   }

   private function prepareSatisfactionSurveyLink(): void
   {
      global $CFG_GLPI;

      // --- Old token generation (weak: crypt() falls back to legacy DES,
      //     keeps only 2 salt chars + 8 input chars, so it's guessable) ---
      // $salt = bin2hex(random_bytes(5));
      // $this->satisfaction_hash = crypt(
      //    sprintf('?user=%s&ticket=%d', $this->user_id, $this->ticket_id),
      //    $salt
      // );
      // --- New token: 256 bits of cryptographic randomness, unguessable ---
      $this->satisfaction_hash = bin2hex(random_bytes(32));

      $this->satisfaction_survey_link = sprintf(
         "%s/plugins/satisfacao/front/responsepage.form.php?satisfaction=%s",
         $CFG_GLPI["url_base"],
         $this->satisfaction_hash
      );
   }

   private function saveSatisfactionSurveyHash(): void
   {
      global $DB;

      $DB->insert(
         self::SATISFACAO_SURVEY_HASHES_TABLE,
         [
            'ticket_id' => $this->ticket_id,
            'satisfaction_survey_hash' => $this->satisfaction_hash
         ]
      );
   }

   /**
    * Returns the direct satisfaction survey link with the satisfaction level value.
    *
    * @param int $satisfaction_level
    *   Satisfaction level value from 1 to 5.
    *
    * @return string
    * @throws Exception
    */
   public function getSatisfactionSurveyLinkForMailTemplate(int $satisfaction_level): string
   {
      if ($satisfaction_level > 5 || $satisfaction_level < 1) {
         throw new Exception("Invalid satisfaction level value. The value must be between 1 and 5.");
      }

      return $this->satisfaction_survey_link . sprintf('&satisfactionLevel=%d', $satisfaction_level);
   }

   public static function getTicketIdBySatisfactionSurveyHash(string $satisfaction_survey_hash): string
   {
      global $DB;

      $db_query = $DB->request(
         [
            'SELECT' => 'ticket_id',
            'FROM' => self::SATISFACAO_SURVEY_HASHES_TABLE,
            'WHERE' => ['satisfaction_survey_hash' => $satisfaction_survey_hash]
         ]
      );

      $ticket_id = '';
      foreach ($db_query as $ticket) {
         $ticket_id = $ticket['ticket_id'];
      }

      return $ticket_id;
   }

   /**
    * Deletes a survey hash so the link becomes single-use.
    *
    * @param string $satisfaction_survey_hash
    * @return void
    */
   public static function deleteSatisfactionSurveyHash(string $satisfaction_survey_hash): void
   {
      global $DB;

      $DB->delete(
         self::SATISFACAO_SURVEY_HASHES_TABLE,
         ['satisfaction_survey_hash' => $satisfaction_survey_hash]
      );
   }
}
