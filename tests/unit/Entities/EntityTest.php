<?php
namespace Tests\Unit\Entities;

use CodeIgniter\Test\CIUnitTestCase;
use App\Entities\User;
use App\Entities\Media;

final class EntityTest extends CIUnitTestCase
{
// ── USER ──────────────────────────────────────────

/**
* Cas nominal : getFullName concatène prénom + nom
*/
public function testGetFullNameReturnsConcatenatedName(): void
{
$user = new User(['first_name' => 'Jean', 'last_name' => 'Dupont']);

$this->assertEquals('Jean Dupont', $user->getFullName());
}

/**
* Cas limite : isActive retourne TRUE quand deleted_at est NULL
* et FALSE quand deleted_at est renseigné
*/
public function testIsActiveChecksDeletedAt(): void
{
$active   = new User(['deleted_at' => null]);
$inactive = new User(['deleted_at' => '2025-01-01 00:00:00']);

$this->assertTrue($active->isActive());
$this->assertFalse($inactive->isActive());
}

// ── MEDIA ─────────────────────────────────────────

/**
* Cas nominal : isImage reconnaît les extensions image courantes
*/
public function testIsImageReturnsTrueForImageExtensions(): void
{
$jpg  = new Media(['file_path' => 'uploads/photo.jpg']);
$png  = new Media(['file_path' => 'uploads/logo.PNG']);
$pdf  = new Media(['file_path' => 'uploads/doc.pdf']);

$this->assertTrue($jpg->isImage());
$this->assertTrue($png->isImage());   // extension en majuscule
$this->assertFalse($pdf->isImage());  // PDF n'est pas une image
}

/**
* Cas invalide : isValidEntityType rejette les types non autorisés
*/
public function testIsValidEntityTypeRejectsUnknownTypes(): void
{
$valid   = new Media(['entity_type' => 'recipe']);
$invalid = new Media(['entity_type' => 'unknown_type']);

$this->assertTrue($valid->isValidEntityType());
$this->assertFalse($invalid->isValidEntityType());
}
}