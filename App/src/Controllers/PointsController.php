<?php
class PointsController {

public function addPoints(Request $request) {
    

$points = $this->pointsCalculator->calculate($request->amount);

$this->pointsModel->add($userId, $points);
return $this->redirect('/dashboard');
}

}