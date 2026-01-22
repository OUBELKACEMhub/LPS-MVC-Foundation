<?php

namespace App\src\Models;

class RewardModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll($availableOnly = false) {
        $sql = "SELECT * FROM rewards";

        if ($availableOnly) {
            $sql .= " WHERE stock > 0 OR stock = -1";
        }

        $sql .= " ORDER BY points_required ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM rewards WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

   
    public function getAffordableRewards($userPoints) {
        $stmt = $this->db->prepare("
            SELECT * FROM rewards 
            WHERE (stock > 0 OR stock = -1) 
            AND points_required <= ? 
            ORDER BY points_required ASC
        ");
        $stmt->execute([$userPoints]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateStock($rewardId, $newStock) {
        $stmt = $this->db->prepare("UPDATE rewards SET stock = ? WHERE id = ?");
        return $stmt->execute([$newStock, $rewardId]);
    }

   
    public function processRedemption($rewardId, $userId) {
        // 1. Récupère le reward
        $reward = $this->findById($rewardId);
        if (!$reward) {
            return ['success' => false, 'reward' => null, 'error' => 'Reward introuvable.'];
        }

        // 2. Vérifie le stock
        if ($reward['stock'] === 0) {
            return ['success' => false, 'reward' => $reward, 'error' => 'Ce reward est épuisé.'];
        }

        $stmtUser = $this->db->prepare("SELECT total_points FROM users WHERE id = ?");
        $stmtUser->execute([$userId]);
        $user = $stmtUser->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'reward' => $reward, 'error' => 'Utilisateur introuvable.'];
        }

        if ($user['total_points'] < $reward['points_required']) {
            return ['success' => false, 'reward' => $reward, 'error' => 'Pas assez de points.'];
        }

        // ── Début de la transaction PDO ──────────────────────────────
        try {
            $this->db->beginTransaction();

            // 4. Insère une ligne dans points_transactions (type = 'redeemed')
            $stmtTx = $this->db->prepare("
                INSERT INTO points_transactions (user_id, type, amount, description, created_at)
                VALUES (?, 'redeemed', ?, ?, NOW())
            ");
            $stmtTx->execute([
                $userId,
                $reward['points_required'],
                "Rédemption du reward : " . $reward['name']
            ]);

            // 5. Défalque les points dans la table users
            $stmtDeduct = $this->db->prepare("
                UPDATE users SET total_points = total_points - ? WHERE id = ?
            ");
            $stmtDeduct->execute([$reward['points_required'], $userId]);

            // 6. Décrément le stock (seulement si stock != -1)
            if ($reward['stock'] !== -1) {
                $stmtStock = $this->db->prepare("
                    UPDATE rewards SET stock = stock - 1 WHERE id = ?
                ");
                $stmtStock->execute([$rewardId]);
            }

            $this->db->commit();

            $rewardUpdated = $this->findById($rewardId);
            return ['success' => true, 'reward' => $rewardUpdated, 'error' => null];

        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'reward' => $reward, 'error' => 'Erreur lors de la rédemption.'];
        }
    }
}
